<?php
/**
 * Script para extraer archivos .wpress manualmente
 * 
 * USO:
 * 1. Sube este archivo a la raíz de tu WordPress
 * 2. Sube tu archivo .wpress a la misma carpeta
 * 3. Accede desde el navegador: http://tu-sitio.com/extract-wpress.php?file=archivo.wpress
 * 4. O ejecuta por línea de comandos: php extract-wpress.php archivo.wpress
 */

// Configuración
$wpress_file = isset($_GET['file']) ? $_GET['file'] : (isset($argv[1]) ? $argv[1] : null);
$extract_to = __DIR__ . '/extracted-backup/';

if (!$wpress_file) {
    die("Uso: php extract-wpress.php archivo.wpress\nO accede desde navegador: ?file=archivo.wpress\n");
}

if (!file_exists($wpress_file)) {
    die("Error: El archivo '$wpress_file' no existe.\n");
}

echo "Extrayendo archivo: $wpress_file\n";
echo "Destino: $extract_to\n\n";

// Crear directorio de destino
if (!is_dir($extract_to)) {
    mkdir($extract_to, 0755, true);
}

// El archivo .wpress es básicamente un TAR comprimido
// Intentar extraer con diferentes métodos

// Método 1: Usar clase Phar (si está disponible)
if (class_exists('PharData')) {
    try {
        // Renombrar temporalmente
        $temp_file = $extract_to . 'temp.tar';
        copy($wpress_file, $temp_file);
        
        $phar = new PharData($temp_file);
        $phar->extractTo($extract_to);
        
        unlink($temp_file);
        echo "✓ Extracción completada usando PharData\n";
    } catch (Exception $e) {
        echo "Error con PharData: " . $e->getMessage() . "\n";
        echo "Intentando método alternativo...\n";
    }
}

// Método 2: Usar comando tar del sistema (si está disponible)
if (!file_exists($extract_to . 'database.sql')) {
    $command = "cd " . escapeshellarg($extract_to) . " && tar -xzf " . escapeshellarg(__DIR__ . '/' . $wpress_file) . " 2>&1";
    exec($command, $output, $return_var);
    
    if ($return_var === 0) {
        echo "✓ Extracción completada usando tar\n";
    } else {
        echo "Error con tar. Intenta extraer manualmente con 7-Zip o WinRAR.\n";
        echo "Output: " . implode("\n", $output) . "\n";
    }
}

// Verificar qué se extrajo
if (is_dir($extract_to)) {
    $files = scandir($extract_to);
    $files = array_diff($files, ['.', '..']);
    
    echo "\nArchivos extraídos:\n";
    foreach ($files as $file) {
        $path = $extract_to . $file;
        $size = is_file($path) ? filesize($path) : 'DIR';
        echo "  - $file ($size)\n";
    }
    
    // Buscar database.sql
    if (file_exists($extract_to . 'database.sql')) {
        echo "\n✓ Base de datos encontrada: database.sql\n";
        echo "Tamaño: " . number_format(filesize($extract_to . 'database.sql') / 1024 / 1024, 2) . " MB\n";
    }
    
    // Buscar wp-content
    if (is_dir($extract_to . 'wp-content')) {
        echo "\n✓ Carpeta wp-content encontrada\n";
    }
    
    echo "\n✓ Extracción completada en: $extract_to\n";
    echo "\nPRÓXIMOS PASOS:\n";
    echo "1. Sube los archivos extraídos a tu servidor WordPress\n";
    echo "2. Importa database.sql en phpMyAdmin o vía MySQL\n";
    echo "3. Actualiza las URLs si cambió el dominio\n";
} else {
    echo "\n✗ Error: No se pudo extraer el archivo.\n";
    echo "Intenta extraer manualmente con 7-Zip renombrando .wpress a .tar.gz\n";
}


