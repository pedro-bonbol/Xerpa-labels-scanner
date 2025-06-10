<?php

namespace App\Controllers;

class Review extends BaseController
{
    public function index()
    {
        $imagenes = session()->get('imagenes_etiquetas') ?? [];
        return view('labels/revision', ['imagenes' => $imagenes]);
    }

    public function corregir($id)
    {
        $imagenes = session()->get('imagenes_etiquetas') ?? [];

        if (!isset($imagenes[$id])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Etiqueta no encontrada");
        }

        $etiqueta = $imagenes[$id];

        // Puedes mostrar un formulario editable con los datos
        return view('labels/editar_revision', ['etiqueta' => $etiqueta, 'id' => $id]);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id');
        $imagenes = session()->get('imagenes_etiquetas') ?? [];

        if (!isset($imagenes[$id])) {
            return redirect()->to('/review')->with('error', 'Etiqueta no válida');
        }

        // Actualizar campos
        $imagenes[$id]['extraido'] = [
            'producto'    => $this->request->getPost('producto'),
            'lote'        => $this->request->getPost('lote'),
            'caducidad'   => $this->request->getPost('caducidad'),
            'procedencia' => $this->request->getPost('procedencia'),
            'peso'        => $this->request->getPost('peso'),
        ];

        session()->set('imagenes_etiquetas', $imagenes);

        return redirect()->to('/review');
    }

    public function guardarRevision()
    {
        $datos = $this->request->getPost('datos');
        // Aquí puedes guardar en base de datos o continuar flujo
        // Guardamos en sesión por ahora para conservarlo:
        session()->set('revision_final', $datos);

        return redirect()->to('etiquetas/confirmacion');
    }
}
