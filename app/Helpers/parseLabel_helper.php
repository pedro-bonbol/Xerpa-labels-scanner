<?php

function parseLabel(string $texto): array
{
    $resultado = [
        'producto'     => '',
        'peso'         => '',
        'lote'         => '',
        'caducidad'    => '',
        'procedencia'  => '',
    ];

    // Debug crucial
    log_message('debug', 'Texto OCR original: ' . $texto);
    
    // Normalización específica para Cloud Vision
    $textoLimpio = preg_replace('/\s+/', ' ', trim($texto));
    log_message('debug', 'Texto normalizado: ' . $textoLimpio);

    // Orden de prioridad para campos críticos
    $resultado['lote'] = extraerLoteMejorado($textoLimpio);
    $resultado['peso'] = extraerPesoMejorado($textoLimpio);
    $resultado['caducidad'] = extraerCaducidadMejorado($textoLimpio);
    $resultado['procedencia'] = extraerProcedenciaMejorado($textoLimpio);
    $resultado['producto'] = extraerProductoMejorado($textoLimpio);

    log_message('debug', 'Resultado final: ' . print_r($resultado, true));
    return $resultado;
}

function extraerProductoMejorado(string $texto): string
{
    // Estrategia 1: Buscar "Producto :" específicamente
    if (preg_match('/producto\s*:\s*([^\n\r]+)/i', $texto, $match)) {
        return trim($match[1]);
    }
    
    // Estrategia 2: Detectar productos específicos por palabras clave
    if (preg_match('/calamar\s+entero\s+ultra\s+congelado/i', $texto)) {
        return 'CALAMAR ENTERO ULTRA CONGELADO ENTUBADO';
    }
    
    if (preg_match('/pechuga\s+de\s+pollo/i', $texto)) {
        return 'PECHUGA DE POLLO';
    }
    
    // Estrategia 3: Primera línea con más de 10 caracteres sin códigos
    $lineas = explode("\n", $texto);
    foreach ($lineas as $linea) {
        $lineaLimpia = trim($linea);
        if (strlen($lineaLimpia) > 10 && 
            !preg_match('/\d{4,}|lote|peso|consumir|fecha/i', $lineaLimpia)) {
            return $lineaLimpia;
        }
    }
    
    return '';
}

function extraerPesoMejorado(string $texto): string
{
    // Formato 1: "Peso Neto : 6x1.8Kgs."
    if (preg_match('/peso\s+neto\s*:\s*(\d+x\d+[\.,]\d+)\s*kgs?\.?/i', $texto, $match)) {
        return str_replace(',', '.', $match[1]);
    }
    
    // Formato 2: "Peso neto: 1,110kg"
    if (preg_match('/peso\s*neto\s*[:\-]?\s*([\d\.,]+)\s*kg/i', $texto, $match)) {
        return str_replace(',', '.', $match[1]);
    }
    
    // Formato 3: Cualquier peso con kg
    if (preg_match('/(\d+[\.,]\d+)\s*kg/i', $texto, $match)) {
        return str_replace(',', '.', $match[1]);
    }
    
    return '';
}

function extraerLoteMejorado(string $texto): string
{
    // Formato 1: "Lote : SP-2785-034"
    if (preg_match('/lote\s*:\s*([A-Z]{2}-\d{4}-\d{3})/i', $texto, $match)) {
        return $match[1];
    }
    
    // Formato 2: "Lote: 03000023570"
    if (preg_match('/lote\s*[:\-]?\s*([A-Z0-9]{6,})/i', $texto, $match)) {
        return $match[1];
    }
    
    // Formato 3: Lote general
    if (preg_match('/lote\s*:\s*([A-Z0-9\-]{5,15})/i', $texto, $match)) {
        return $match[1];
    }
    
    return '';
}

function extraerCaducidadMejorado(string $texto): string
{
    // Formato 1: "Consumir Preferentemente Antes De: 07 OCTUBRE 2023"
    if (preg_match('/consumir\s+preferentemente\s+antes\s+de\s*:\s*(\d{1,2})\s+(octubre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|noviembre|diciembre)\s+(\d{4})/i', $texto, $match)) {
        $dia = str_pad($match[1], 2, '0', STR_PAD_LEFT);
        $mes = convertirMesEspanol($match[2]);
        return "$mes/$match[3]";
    }
    
    // Formato 2: "Consumir preferentemente antes del: 12.2020"
    if (preg_match('/consumir\s+preferentemente\s+antes\s+del\*?\s*[:\-]?\s*(\d{1,2})[\.\/\s](\d{4})/i', $texto, $match)) {
        $mes = str_pad($match[1], 2, '0', STR_PAD_LEFT);
        return "$mes/{$match[2]}";
    }
    
    // Formato 3: Solo buscar fechas con mes en texto
    if (preg_match('/(\d{1,2})\s+(octubre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|noviembre|diciembre)\s+(\d{4})/i', $texto, $match)) {
        $dia = str_pad($match[1], 2, '0', STR_PAD_LEFT);
        $mes = convertirMesEspanol($match[2]);
        return "$mes/$match[3]";
    }
    
    return '';
}

function extraerProcedenciaMejorado(string $texto): string
{
    // Formato 1: "País de origen : India"
    if (preg_match('/pa[ií]s\s+de\s+origen\s*:\s*([a-záéíóúñ]+)/i', $texto, $match)) {
        return ucfirst(strtolower(trim($match[1])));
    }
    
    // Formato 2: Buscar países específicos directamente
    $paises = [
        'india' => 'India',
        'españa' => 'España',
        'espana' => 'España',
        'argentina' => 'Argentina',
        'chile' => 'Chile'
    ];
    
    foreach ($paises as $buscar => $nombre) {
        if (preg_match('/\b' . $buscar . '\b/i', $texto)) {
            return $nombre;
        }
    }
    
    return '';
}

function extraerCodigoMejorado(string $texto): string
{
    // Formato 1: "1K08S3/80A"
    if (preg_match('/\b(\d[A-Z]\d+[A-Z]\d+\/\d+[A-Z])\b/', $texto, $match)) {
        return $match[1];
    }
    
    // Formato 2: Códigos con barras como "1K08S3/80A"
    if (preg_match('/\b([0-9][A-Z0-9]{4,}\/[0-9A-Z]+)\b/', $texto, $match)) {
        return $match[1];
    }
    
    // Formato 3: Cualquier código alfanumérico con barra
    if (preg_match('/\b([A-Z0-9]{6,}\/[A-Z0-9]+)\b/', $texto, $match)) {
        return $match[1];
    }
    
    return '';
}


function convertirMesEspanol(string $mes): string 
{
    $meses = [
        'enero' => '01', 'febrero' => '02', 'marzo' => '03',
        'abril' => '04', 'mayo' => '05', 'junio' => '06',
        'julio' => '07', 'agosto' => '08', 'septiembre' => '09',
        'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12'
    ];
    return $meses[mb_strtolower($mes)] ?? '01';
}
