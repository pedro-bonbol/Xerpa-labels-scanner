<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Etiqueta Estandarizada</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 40px; }
    .label { width: 500px; border: 1px solid #000; padding: 20px; }
    .qr { float: right; width: 100px; }
    .line { margin: 8px 0; }
    .title { font-size: 18px; font-weight: bold; }
    .subtitle { font-size: 16px; margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="label">
    <div style="display: flex; justify-content: space-between;">
      <div>
        <div class="title">COMERCIAL MARTEL</div>
        <div class="subtitle"><?= $data['producto'] ?></div>
      </div>
      <div>
        <img class="qr" src="<?= $qr ?>" alt="QR">
      </div>
    </div>

    <div class="line">Origen: <?= $data['origen'] ?></div>
    <div class="line">Peso: <?= $data['peso'] ?></div>
    <div class="line">Lote: <?= $data['lote'] ?></div>
    <div class="line">Fecha congelación: <?= $data['congelacion'] ?></div>
    <div class="line">Consumir antes de: <?= $data['caducidad'] ?></div>
    <div class="line">Empresa: <?= $data['empresa'] ?></div>
  </div>
</body>
</html>
