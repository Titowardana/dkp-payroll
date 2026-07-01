<?php

/**
 * Autoloader bridge.
 *
 * 1. Muat autoloader Composer (jika ada) → untuk Dompdf, dll.
 * 2. Tambah helper lokal (Composer\Pcre\Preg, Psr\SimpleCache\CacheInterface).
 * 3. Tambah autoloader manual untuk PhpSpreadsheet (jika belum dipasang via Composer).
 */

// 1. Composer autoload (jika ada)
// Penting: autoload_real.php harus memanggil getLoader() agar autoloader ter-register.
$composerAutoloadReal = __DIR__ . '/composer/autoload_real.php';
if (file_exists($composerAutoloadReal)) {
    require_once $composerAutoloadReal;
    foreach (get_declared_classes() as $cls) {
        if (strncmp($cls, 'ComposerAutoloaderInit', 20) === 0 && method_exists($cls, 'getLoader')) {
            $cls::getLoader();
            break;
        }
    }
}

// Jika Composer sudah membuat vendor/autoload.php yang berbeda,
// baris di atas aman karena file ini hanya sebagai bridge di project ini.

// 2. Helper lokal untuk PhpSpreadsheet
$composerPcre = __DIR__ . '/ComposerPcrePreg.php';
if (file_exists($composerPcre)) {
    require_once $composerPcre;
}

$psrSimpleCache = __DIR__ . '/PsrSimpleCacheCacheInterface.php';
if (file_exists($psrSimpleCache)) {
    require_once $psrSimpleCache;
}

// 3. Autoloader manual untuk PhpSpreadsheet (jika diperlukan)
$phpspreadsheetAutoload = __DIR__ . '/PhpSpreadsheet-5.4.0/autoload.php';
if (file_exists($phpspreadsheetAutoload)) {
    require_once $phpspreadsheetAutoload;
}

// 4. Tambah autoloader sederhana untuk Dompdf dan dependensinya
spl_autoload_register(function ($class) {
    // Dompdf\
    $prefixes = [
        'Dompdf\\'  => __DIR__ . '/dompdf/dompdf/src/',
        'FontLib\\' => __DIR__ . '/dompdf/php-font-lib/src/FontLib/',
        'Svg\\'     => __DIR__ . '/dompdf/php-svg-lib/src/Svg/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
});

// Dompdf juga memakai classmap "lib/" (mis. Dompdf\Cpdf).
// Tangani kasus ini secara khusus.
spl_autoload_register(function ($class) {
    if (strncmp('Dompdf\\', $class, 6) !== 0) {
        return;
    }

    // Contoh: Dompdf\Cpdf berada di vendor/dompdf/dompdf/lib/Cpdf.php (bukan di src/)
    $relative = substr($class, 6);
    if (strpos($relative, '\\') === false) {
        $libFile = __DIR__ . '/dompdf/dompdf/lib/' . $relative . '.php';
        if (file_exists($libFile)) {
            require $libFile;
        }
    }
});

