<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Revisión de datos</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .card-input:focus {
      transform: scale(1.01);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <!-- Progress Bar Component -->
  <?= view('components/progress_bar') ?>


  <section class="max-w-7xl mx-auto py-4 md:py-10 px-4">
    <div class="mb-6">
      <h2 class="text-xl md:text-2xl font-bold mb-2 md:mb-4">Procesamiento y revisión de datos</h2>
      <p class="text-sm md:text-base text-gray-600">
        Revisa y edita los datos extraídos automáticamente. Puedes modificar cualquier campo si la información no es correcta.
      </p>
    </div>

    <form action="<?= base_url('etiquetas/guardarRevision') ?>" method="post">

      <!-- Vista Desktop - Tabla -->
      <div class="hidden md:block">
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
          <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
              <tr>
                <th class="p-3 text-center">#</th>
                <th class="p-3">Producto</th>
                <th class="p-3">Código Interno</th>
                <th class="p-3">Peso (kg)</th>
                <th class="p-3">Lote</th>
                <th class="p-3">Caducidad</th>
                <th class="p-3">Editar</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($imagenes as $index => $img):
                $datos = $img['extraido'] ?? ['producto' => '', 'peso' => '', 'lote' => '', 'caducidad' => '', 'procedencia' => '', 'codigo' => ''];
              ?>
                <tr class="border-b hover:bg-gray-50" data-index="<?= $index ?>">
                  <td class="p-3 text-center font-medium"><?= $index + 1 ?></td>
                  <td class="p-3">
                    <div class="editable-cell" data-index="<?= $index ?>" data-field="producto">
                      <span class="display-text"><?= esc($datos['producto']) ?></span>
                      <input
                        type="text"
                        name="datos[<?= $index ?>][producto]"
                        value="<?= esc($datos['producto']) ?>"
                        class="edit-input w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="editable-cell" data-index="<?= $index ?>" data-field="codigo">
                      <span class="display-text"></span>
                      <input
                        type="text"
                        class="w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="editable-cell" data-index="<?= $index ?>" data-field="peso">
                      <span class="display-text"><?= esc($datos['peso']) ?></span>
                      <input name="datos[<?= $index ?>][peso]" value="<?= esc($datos['peso']) ?>"
                        class="w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="editable-cell" data-index="<?= $index ?>" data-field="lote">
                      <span class="display-text"><?= esc($datos['lote']) ?></span>
                      <input name="datos[<?= $index ?>][lote]" value="<?= esc($datos['lote']) ?>"
                        class="w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="editable-cell" data-index="<?= $index ?>" data-field="caducidad">
                      <span class="display-text"><?= esc($datos['caducidad']) ?></span>
                      <input name="datos[<?= $index ?>][caducidad]" value="<?= esc($datos['caducidad']) ?>"
                        class="w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                    </div>
                  </td>
                  <td class="p-3 text-center">
                    <button id="edit-btn" type="button" class="edit-btn text-blue-600 hover:text-blue-800" data-index="<?= $index ?>">
                      ✏️
                    </button>
                    <button id="save-btn" type="button" class="save-btn text-green-600 hover:text-green-800 hidden" data-index="<?= $index ?>">
                      ✅
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vista Mobile - Cards -->
      <div class="md:hidden space-y-4">
        <?php foreach ($imagenes as $index => $img):
          $datos = $img['extraido'] ?? ['producto' => '', 'peso' => '', 'lote' => '', 'caducidad' => '', 'procedencia' => '', 'codigo' => ''];
        ?>
          <div class="bg-white rounded-lg shadow-sm border p-4 mb-4">
            <div class="flex items-center justify-between mb-4">

              <span class="text-sm font-semibold text-gray-500">Producto <?= $index + 1 ?></span>
              <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold">
                <div>
                  <button id="edit-btn" type="button" class="edit-btn text-blue-600 hover:text-blue-800" data-index="<?= $index ?>">
                    ✏️
                  </button>
                  <button id="save-btn" type="button" class="save-btn text-green-600 hover:text-green-800 hidden" data-index="<?= $index ?>">
                    ✅
                  </button>
                </div>
                <?= $index + 1 ?>
              </div>
            </div>

            <div class="space-y-4">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Producto</label>
                <div class="editable-cell" data-index="<?= $index ?>" data-field="producto">
                  <span class="display-text font-bold"><?= $datos['producto'] !== '' ? esc($datos['producto']) : 'Sin contenido' ?></span>
                  <input
                    type="text"
                    name="datos[<?= $index ?>][producto]"
                    value="<?= esc($datos['producto']) ?>"
                    class="edit-input w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                </div>

              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Código</label>
                  <div class="editable-cell" data-index="<?= $index ?>" data-field="codigo">
                    <span class="display-text"></span>
                    <input type="text"
                      class="w-full border rounded p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 hidden" />
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Peso (kg)</label>
                  <div class="editable-cell" data-index="<?= $index ?>" data-field="peso">
                    <span class="display-text"><?= $datos['peso'] !== '' ? esc($datos['peso']) : 'Sin contenido' ?></span>

                    <input name="datos[<?= $index ?>][peso]" value="<?= esc($datos['peso']) ?>"
                      class="card-input w-full border rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 hidden" />
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Lote</label>
                  <div class="editable-cell" data-index="<?= $index ?>" data-field="lote">
                    <span class="display-text"><?= $datos['lote'] !== '' ? esc($datos['lote']) : 'Sin contenido' ?></span>
                    <input name="datos[<?= $index ?>][lote]" value="<?= esc($datos['lote']) ?>"
                      class="card-input w-full border rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 hidden" />
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Caducidad</label>
                <div class="editable-cell" data-index="<?= $index ?>" data-field="caducidad">
                  <span class="display-text"> <?= esc($datos['caducidad'] !== '' ? $datos['caducidad'] : 'Sin contenido') ?>
                  </span>
                  <input name="datos[<?= $index ?>][caducidad]" value="<?= esc($datos['caducidad']) ?>"
                    class="card-input w-full border rounded-lg p-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 hidden" />
                </div>
              </div>
            </div>
          </div>
      </div>
    <?php endforeach; ?>
    </div>

    <!-- Botón de envío -->
    <div class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
      <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
        Guardar y continuar →
      </button>
    </div>
    </form>
  </section>

  <script>
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        const index = button.getAttribute('data-index');
        const row = document.querySelector(`tr[data-index="${index}"]`);
        const cells = row.querySelectorAll('.editable-cell');

        cells.forEach(cell => {
          const span = cell.querySelector('.display-text');
          const input = cell.querySelector('input');
          if (span) span.classList.add('hidden');
          if (input) input.classList.remove('hidden');
        });

        // Alternar botones
        button.classList.add('hidden');
        const saveBtn = row.querySelector('.save-btn');
        if (saveBtn) saveBtn.classList.remove('hidden');
      });
    });

    document.querySelectorAll('.save-btn').forEach(button => {
      button.addEventListener('click', () => {
        const index = button.getAttribute('data-index');
        const row = document.querySelector(`tr[data-index="${index}"]`);
        const cells = row.querySelectorAll('.editable-cell');

        cells.forEach(cell => {
          const span = cell.querySelector('.display-text');
          const input = cell.querySelector('input');
          if (span && input) {
            span.textContent = input.value;
            span.classList.remove('hidden');
            input.classList.add('hidden');
          }
        });

        // Alternar botones
        button.classList.add('hidden');
        const editBtn = row.querySelector('.edit-btn');
        if (editBtn) editBtn.classList.remove('hidden');
      });
    });
  </script>
</body>

</html>