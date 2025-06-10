<?php

namespace App\Controllers;


use Firebase\JWT\JWT;


class Etiquetas extends BaseController
{


    public function index(): string
    {
        return view('capture');
    }


    private function getAccessToken(string $credentialsPath): ?string
    {
        $json = json_decode(file_get_contents($credentialsPath), true);

        $now = time();
        $payload = [
            'iss'   => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud'   => $json['token_uri'],
            'exp'   => $now + 3600,
            'iat'   => $now,
        ];

        $jwt = \Firebase\JWT\JWT::encode($payload, $json['private_key'], 'RS256');

        $response = file_get_contents($json['token_uri'], false, stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt,
                ])
            ]
        ]));

        $decoded = json_decode($response, true);
        return $decoded['access_token'] ?? null;
    }



    public function procesar()
    {
        helper('parseLabel');

        $response = ['success' => false];
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 10240; // 10MB
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $imagenesProcesadas = [];
        $apiKey = getenv('GOOGLE_VISION_API_KEY');

        // USANDO $_FILES['files'] según tu JS
        if (!isset($_FILES['files'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => ['No se recibió ningún archivo.']
            ]);
        }

        foreach ($_FILES['files']['name'] as $i => $nombreOriginal) {
            $tempName = $_FILES['files']['tmp_name'][$i];
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreFinal = uniqid('img_', true) . '.' . $extension;
            $destino = $config['upload_path'] . $nombreFinal;

            if (move_uploaded_file($tempName, $destino)) {
                $imageData = base64_encode(file_get_contents($destino));

                // Construir payload para la API de Vision
                $payload = [
                    'requests' => [
                        [
                            'image' => ['content' => $imageData],
                            'features' => [['type' => 'TEXT_DETECTION']]
                        ]
                    ]
                ];

                // Enviar a la API de Google Vision
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://vision.googleapis.com/v1/images:annotate?key=' . $apiKey);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                $result = curl_exec($ch);

                if (curl_errno($ch)) {
                    $response['error'][] = 'Error de conexión con Google Vision: ' . curl_error($ch);
                    curl_close($ch);
                    continue;
                }

                curl_close($ch);

                $resultData = json_decode($result, true);
                $texto = $resultData['responses'][0]['fullTextAnnotation']['text']
                    ?? ($resultData['responses'][0]['textAnnotations'][0]['description'] ?? '');

            
                $estructura = parseLabel($texto);


                $imagenesProcesadas[] = [
                    'archivo'   => $nombreFinal,
                    'texto'     => $texto,
                    'extraido'  => $estructura
                ];

            } else {
                $response['error'][] = "No se pudo subir la imagen: $nombreOriginal";
            }
        }

        if (!empty($imagenesProcesadas)) {
            session()->set('imagenes_etiquetas', $imagenesProcesadas);
            $response['success'] = true;
            $response['imagenes'] = $imagenesProcesadas;
        }

        return $this->response->setJSON($response);
    }

    public function review()
    {
        $imagenes = session()->get('imagenes_etiquetas') ?? [];
        return view('/labels/review', ['imagenes' => $imagenes]);
    }
}
