<?php

namespace App\Controllers;



class Etiquetas extends BaseController {

   
    public function index(): string
    {
        return view('labels/capture');
    }

    public function procesar() {
        $response = ['success' => false];

        // Configuración básica de subida
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 10240; // 10MB
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->upload->initialize($config);

        $imagenesGuardadas = [];

        // Manejo múltiple
        foreach ($_FILES['imagenes']['name'] as $i => $nombreOriginal) {
            $_FILES['archivo_temporal']['name']     = $_FILES['imagenes']['name'][$i];
            $_FILES['archivo_temporal']['type']     = $_FILES['imagenes']['type'][$i];
            $_FILES['archivo_temporal']['tmp_name'] = $_FILES['imagenes']['tmp_name'][$i];
            $_FILES['archivo_temporal']['error']    = $_FILES['imagenes']['error'][$i];
            $_FILES['archivo_temporal']['size']     = $_FILES['imagenes']['size'][$i];

            if ($this->upload->do_upload('archivo_temporal')) {
                $datos = $this->upload->data();
                $imagenesGuardadas[] = $datos['file_name'];
            } else {
                $response['error'][] = $this->upload->display_errors('', '');
            }
        }

        if (!empty($imagenesGuardadas)) {
            // Guardar en sesión para mostrar en la siguiente pantalla
            $this->session->set_userdata('imagenes_etiquetas', $imagenesGuardadas);
            $response['success'] = true;
        }

        echo json_encode($response);
    }

    public function revision() {
        $imagenes = $this->session->userdata('imagenes_etiquetas') ?? [];
        $this->load->view('etiquetas/revision', ['imagenes' => $imagenes]);
    }
}
