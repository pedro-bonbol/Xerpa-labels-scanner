<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Captura de etiquetas</title>
  <link rel="stylesheet" href="<?= base_url('css/tailwind.css') ?>">

</head>
<body class="bg-gray-50 text-gray-800">
    <?= view('components/progress_bar') ?>

  <section class="max-w-4xl mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-4">Captura etiquetas</h2>
    <p class="mb-6 text-gray-600">
      Captura o sube las etiquetas de los productos. Se mantendrá el orden de subida para asegurar trazabilidad.
    </p>

    <div class="border-2 border-dashed border-gray-300 rounded-lg p-10 text-center">
      <div class="text-4xl mb-4 text-gray-400">⬆️</div>
      <p class="text-gray-500 mb-4">Arrastra y suelta etiquetas aquí</p>

      <!-- Inputs ocultos -->
      <input
        type="file"
        id="fileInputGaleria"
        accept="image/png, image/jpeg, image/gif"
        multiple
        hidden
      />
      <input
        type="file"
        id="fileInputCamara"
        accept="image/*"
        capture="environment"
        hidden
      />

      <!-- Botones -->
      <div class="flex justify-center gap-4">
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          onclick="document.getElementById('fileInputGaleria').click();"
        >
          Seleccionar archivos
        </button>
        <button
            id="btn-camara"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            onclick="document.getElementById('fileInputCamara').click();"
        >
          Usar cámara
        </button>
      </div>

      <p class="mt-4 text-sm text-gray-500">
        PNG, JPG, GIF hasta 10MB (máximo 5 imágenes a la vez)
      </p>
    </div>

    <!-- Previsualización -->
    <div id="preview" class="grid grid-cols-2 gap-4 mt-6"></div>

    <div class="mt-8 text-right">
      <button
        class="bg-blue-200 text-blue-800 px-4 py-2 rounded disabled:opacity-50"
        id="btn-siguiente"
        disabled
        onclick="procesarImagenes();"
      >
        Siguiente →
      </button>
    </div>
  </section>

  <script>
    let archivos = []

    const inputGaleria = document.getElementById('fileInputGaleria')
    const inputCamara = document.getElementById('fileInputCamara')
    const preview = document.getElementById('preview')
    const btnSiguiente = document.getElementById('btn-siguiente')

    inputGaleria.addEventListener('change', manejarSeleccion)
    inputCamara.addEventListener('change', manejarSeleccion)

    function manejarSeleccion(e) {
      const nuevosArchivos = Array.from(e.target.files)

      if (archivos.length + nuevosArchivos.length > 5) {
        alert('Máximo 5 imágenes en total.')
        return
      }

      nuevosArchivos.forEach(file => {
        archivos.push(file)
        mostrarPreview(file)
      })

      btnSiguiente.disabled = archivos.length === 0
    }

    function mostrarPreview(file) {
      const reader = new FileReader()
      reader.onload = function (e) {
        const img = document.createElement('img')
        img.src = e.target.result
        img.className = 'w-full h-40 object-cover rounded shadow'
        preview.appendChild(img)
      }
      reader.readAsDataURL(file)
    }

    function procesarImagenes() {
      const formData = new FormData()
      archivos.forEach((file) => formData.append('imagenes[]', file))

      fetch('<?= base_url('etiquetas/procesar') ?>', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = "<?= base_url('etiquetas/revision') ?>"
        } else {
          alert('Error procesando imágenes: ' + (data.error?.join(', ') || 'desconocido'))
        }
      })
      .catch(err => alert('Error inesperado: ' + err))
    }

    window.addEventListener('DOMContentLoaded', () => {
        const esMovil = /Mobi|Android|iPhone|iPad/i.test(navigator.userAgent)
        const botonCamara = document.querySelector('#btn-camara')

        if (!esMovil) {
            botonCamara.disabled = true
            botonCamara.classList.add('opacity-50', 'cursor-not-allowed')
            botonCamara.title = "Funcionalidad disponible solo en móviles"
        }
    })
  </script>
</body>
</html>
