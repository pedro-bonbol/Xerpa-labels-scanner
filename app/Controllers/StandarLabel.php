<?php

namespace App\Controllers;

use chillerlan\QRCode\QRCode;



class StandarLabel extends BaseController
{
    public function etiqueta_estandar()
    {
        $datosMock = [
            'producto'    => 'PECHUGA POLLO (G) INTERFOLIADA',
            'origen'      => 'Polonia',
            'peso'        => '12 kg',
            'lote'        => '354/3/24',
            'congelacion' => '20/12/2024',
            'caducidad'   => '19/12/2026',
            'empresa'     => 'Cedrob S.A.'
        ];

        $contenidoQr = implode("\n", [
            "Producto {$datosMock['producto']}",
            "Origen {$datosMock['origen']}",
            "Peso {$datosMock['peso']}",
            "Lote {$datosMock['lote']}",
            "Congelación {$datosMock['congelacion']}",
            "Caducidad {$datosMock['caducidad']}",
            "Empresa {$datosMock['empresa']}"
        ]);

        $qrCode = new QRCode();
        $pngData = $qrCode->render($contenidoQr);

        return view('labels/standar_label', [
            'data' => $datosMock,
            'qr' => $pngData
        ]);
    }
}
