<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>



<section id="scanner-labels" class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-semibold mb-6">Welcome to CodeIgniter 4</h1>
    <p class="text-lg leading-relaxed">The small framework with powerful features.</p>
  </section>




  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const scannerContainer = document.getElementById('scanner-labels');
      scannerContainer.addEventListener('click', () => {
        window.location.href = '/labels/capture';
      });
    });
  </script>

<!-- -->

</body>
</html>
