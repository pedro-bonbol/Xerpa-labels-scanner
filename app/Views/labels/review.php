<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Revisión de datos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <section class="max-w-6xl mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-4">Procesamiento y revisión de datos</h2>
    <p class="mb-6 text-gray-600">
      Revisa y edita los datos extraídos automáticamente. Puedes modificar cualquier campo si la información no es correcta.
    </p>

    <form action="<?= base_url('etiquetas/exportar') ?>" method="post">
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
          <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
            <tr>
              <th class="px-4 py-2">Imagen</th>
              <th class="px-4 py-2">Producto</th>
              <th class="px-4 py-2">Peso (kg)</th>
              <th class="px-4 py-2">Lote</th>
              <th class="px-4 py-2">Caducidad</th>
            </tr>
          </thead>
          <tbody class="text-sm">
            <?php foreach ($imagenes as $i => $img): ?>
              <tr class="border-t">
                <td class="px-4 py-2">
                  <img src="<?= base_url('uploads/temp/' . $img) ?>" class="w-24 h-24 object-cover rounded border" />
                </td>
                <td class="px-4 py-2">
                  <input type="text" name="productos[<?= $i ?>][nombre]" value="Producto detectado <?= $i+1 ?>" class="border px-2 py-1 rounded w-full" required>
                </td>
                <td class="px-4 py-2">
                  <input type="number" step="0.1" name="productos[<?= $i ?>][peso]" value="12" class="border px-2 py-1 rounded w-full" required>
                </td>
                <td class="px-4 py-2">
                  <input type="text" name="productos[<?= $i ?>][lote]" value="354/3/24" class="border px-2 py-1 rounded w-full" required>
                </td>
                <td class="px-4 py-2">
                  <input type="date" name="productos[<?= $i ?>][caducidad]" value="<?= date('Y-m-d', strtotime('+1 year')) ?>" class="border px-2 py-1 rounded w-full" required>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="flex justify-between mt-6">
        <a href="<?= base_url('etiquetas') ?>" class="text-blue-600 hover:underline">← Atrás</a>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Siguiente →
        </button>
      </div>
    </form>
  </section>
</body>
</html>
