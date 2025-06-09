<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Captura de etiquetas</title>
  <link rel="stylesheet" href="<?= base_url('css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= base_url('resources/css/styles.css') ?>">

</head>

<body class="bg-gray-50 text-gray-800">
  <?= view('components/progress_bar') ?>

  <section class="max-w-4xl mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-4">Captura etiquetas</h2>
    <p class="mb-6 text-gray-600">
      Captura o sube las etiquetas de los productos. Se mantendrá el orden de subida para asegurar trazabilidad.
    </p>



    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-10 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24 " class="mb-4">
        <path fill="currentColor" d="M6 20q-.825 0-1.412-.587T4 18v-2q0-.425.288-.712T5 15t.713.288T6 16v2h12v-2q0-.425.288-.712T19 15t.713.288T20 16v2q0 .825-.587 1.413T18 20zm5-12.15L9.125 9.725q-.3.3-.712.288T7.7 9.7q-.275-.3-.288-.7t.288-.7l3.6-3.6q.15-.15.325-.212T12 4.425t.375.063t.325.212l3.6 3.6q.3.3.288.7t-.288.7q-.3.3-.712.313t-.713-.288L13 7.85V15q0 .425-.288.713T12 16t-.712-.288T11 15z" />
      </svg>
      <p class="text-gray-500 mb-4">Arrastra y suelta etiquetas aquí</p>

      <!-- Inputs ocultos -->
      <input
        type="file"
        id="fileInputGaleria"
        accept="image/png, image/jpeg, image/gif"
        multiple
        hidden />

      <div id="cameraContainer" class="mt-4 hidden">
        <video id="videoCamera" autoplay class="w-full  h-64 p-1 bg-black rounded"></video>
        <div id="photoCapture" class="flex justify-center items-center mt-2 bg-red-500 border-4 p-6 border-black rounded-full text-white w-10 h-10 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="white" d="M12 16.73q1.567 0 2.649-1.081T15.731 13t-1.082-2.649T12 9.269t-2.649 1.082T8.269 13t1.082 2.649T12 16.731m0-1q-1.165 0-1.948-.783T9.269 13t.783-1.948T12 10.269t1.948.783t.783 1.948t-.783 1.948t-1.948.783M4.616 20q-.691 0-1.153-.462T3 18.384V7.616q0-.691.463-1.153T4.615 6h2.958l1.85-2h5.154l1.85 2h2.958q.69 0 1.152.463T21 7.616v10.769q0 .69-.463 1.153T19.385 20zm0-1h14.769q.269 0 .442-.173t.173-.442V7.615q0-.269-.173-.442T19.385 7h-3.397l-1.844-2H9.856L8.012 7H4.615q-.269 0-.442.173T4 7.616v10.769q0 .269.173.442t.443.173M12 13" />
          </svg>
        </div>
        <canvas id="canvasPhoto" class="hidden"></canvas>
      </div>

      <!-- Botones -->
      <div class="flex flex-col md:flex-row justify-center gap-4">
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          onclick="document.getElementById('fileInputGaleria').click();">
          Seleccionar archivos
        </button>
        <button id="openCamera" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Usar Cámara
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
        id="next-btn"
        disabled
        onclick="procesarImagenes();">
        Siguiente →
      </button>
    </div>
  </section>

  <script>
    let files = []
    let stream

    const inputGaleria = document.getElementById('fileInputGaleria')
    const preview = document.getElementById('preview')
    const nextBtn = document.getElementById('next-btn')


    const openCamera = document.getElementById('openCamera')
    const cameraContainer = document.getElementById('cameraContainer')
    const videoCamera = document.getElementById('videoCamera')
    const photoCapture = document.getElementById('photoCapture')
    const canvasPhoto = document.getElementById('canvasPhoto')


    openCamera.addEventListener('click', async () => {
      cameraContainer.classList.remove('hidden')
      cameraContainer.classList.add('flex', 'flex-col', 'items-center', 'justify-center', 'mb-4')
      stream = await navigator.mediaDevices.getUserMedia({
        video: true
      })
      videoCamera.srcObject = stream
    })

    photoCapture.addEventListener('click', () => {
      canvasPhoto.width = videoCamera.videoWidth
      canvasPhoto.height = videoCamera.videoHeight
      canvasPhoto.getContext('2d').drawImage(videoCamera, 0, 0)
      canvasPhoto.classList.remove('hidden')
      stream.getTracks().forEach(track => track.stop())
      const dataUrl = canvasPhoto.toDataURL('image/png')
    })

    inputGaleria.addEventListener('change', manageFiles)

    function manageFiles(e) {
      const newFiles = Array.from(e.target.files)

      if (files.length + newFiles.length > 5) {
        alert('Máximo 5 imágenes en total.')
        return
      }

      newFiles.forEach(file => {
        files.push(file)
        showPreview(file)
      })

      nextBtn.disabled = files.length === 0
    }

    function showPreview(file) {
      const reader = new FileReader()
      reader.onload = function(e) {
        const img = document.createElement('img')
        img.src = e.target.result
        img.className = 'w-full h-40 object-cover rounded shadow'
        preview.appendChild(img)
      }
      reader.readAsDataURL(file)
    }

    function manageCamera(e) {
      navigator.mediaDevices.getUserMedia({
          video: true
        })
        .then(stream => {
          document.getElementById('video').srcObject = stream;
        });
    }

    function processImages() {
      const formData = new FormData()
      files.forEach((file) => formData.append('files[]', file))

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
  </script>
</body>

</html>