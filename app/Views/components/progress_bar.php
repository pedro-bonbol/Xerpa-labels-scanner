<?php
  // echo "<p>Ruta: $ruta — Paso actual: $indiceActual</p>";
  $ruta = service('uri')->getSegment(2) ?? 'index';

  $pasos = [
    'capture' => [
      'titulo' => 'Captura etiquetas',
      'icono' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3a2 2 0 012-2h10a2 2 0 012 2v4h-2V3H5v14h10v-4h2v4a2 2 0 01-2 2H5a2 2 0 01-2-2V3zm6 8V7h2v4h3l-4 4-4-4h3z"/></svg>'
    ],
    'review' => [
      'titulo' => 'Revisión de datos',
      'icono' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3a2 2 0 00-2 2v3a2 2 0 002 2v9a2 2 0 002 2h10a2 2 0 002-2v-9a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h14v3H5V5z"/></svg>'
    ],
    'export' => [
      'titulo' => 'Exportación',
      'icono' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4a1 1 0 01.894.553l6 12A1 1 0 0118 18H6a1 1 0 01-.894-1.447l6-12A1 1 0 0112 4zm0 2.618L7.618 16h8.764L12 6.618z"/></svg>'
    ],
  ];

  $claves = array_keys($pasos);
  $indiceActual = array_search($ruta, $claves);
?>

<div class="flex items-center justify-center max-w-5xl mx-auto px-4 py-6">

  <?php foreach ($pasos as $clave => $paso): ?>
    <?php
      $index = array_search($clave, $claves);
      $estado = $index < $indiceActual ? 'completado' : ($index === $indiceActual ? 'activo' : 'futuro');

      $circleClass = match ($estado) {
        'completado' => 'bg-green-500 text-white',
        'activo' => 'bg-blue-600 text-white',
        'futuro' => 'bg-white border border-gray-300 text-gray-400'
      };

      $circleIcon = match ($estado) {
        'completado' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>',
        'activo', 'futuro' => $paso['icono']
      };

      $textClass = $estado === 'futuro' ? 'text-gray-500' : 'text-black';
    ?>

    <div class="flex items-center w-full last:w-auto relative">
      <!-- Paso -->
      <div class="flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-full flex items-center justify-center <?= $circleClass ?>">
          <?= $circleIcon ?>
        </div>
        <div class="text-xs mt-2 w-24 <?= $textClass ?>">
          <?= $paso['titulo'] ?>
        </div>
      </div>

      <!-- Línea -->
      <?php if ($index < count($pasos) - 1): ?>
        <div class="flex-1 h-1 ml-4 mr-4 <?= $index < $indiceActual ? 'bg-green-500' : 'bg-gray-300' ?>"></div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
