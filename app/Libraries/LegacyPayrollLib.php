<?php namespace App\Libraries;

class LegacyPayrollLib
{
    public static function load(): void
    {
        $appPath = defined('APPPATH') ? APPPATH : dirname(__DIR__) . '/';
        $autoload = $appPath . 'ThirdParty/legacy_vendor/autoload.php';
        if (file_exists($autoload)) {
            require_once $autoload;
        }

        $cpdfPath = $appPath . 'ThirdParty/legacy_vendor/dompdf/dompdf/lib/Cpdf.php';
        if (file_exists($cpdfPath) && !class_exists('Dompdf\Cpdf', false)) {
            require_once $cpdfPath;
        }

        $ioFactoryPath = $appPath . 'ThirdParty/legacy_vendor/PhpSpreadsheet-5.4.0/src/PhpSpreadsheet/IOFactory.php';
        if (! class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class) && file_exists($ioFactoryPath)) {
            require_once $ioFactoryPath;
        }
    }
}
