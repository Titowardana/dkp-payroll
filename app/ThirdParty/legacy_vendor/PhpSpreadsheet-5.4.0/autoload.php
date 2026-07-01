<?php
// Autoloader sederhana untuk PhpSpreadsheet (install manual tanpa Composer)

// PSR-4 style autoloader untuk namespace PhpOffice\PhpSpreadsheet
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $baseDir = __DIR__ . '/src/PhpSpreadsheet/';

    // Hanya handle kelas dengan prefix di atas
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Ubah namespace ke path file
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Autoload kelas lokal project (jika ada di folder includes)
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/../includes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
