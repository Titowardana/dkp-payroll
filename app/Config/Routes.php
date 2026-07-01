<?php namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// === KHUSUS ADMIN ===
$routes->group('', ['filter' => 'admin'], function($routes) {
    // Pegawai
    $routes->get('pegawai', 'Pegawai::index');
    $routes->post('pegawai/save', 'Pegawai::save');
    $routes->get('pegawai/detail/(:num)', 'Pegawai::detail/$1');
    $routes->post('pegawai/delete/(:num)', 'Pegawai::delete/$1');

    // Backup
    $routes->get('backup', 'Backup::index');
    $routes->post('backup/create', 'Backup::create');
    $routes->get('backup/download/(:segment)', 'Backup::download/$1');
    $routes->post('backup/delete/(:segment)', 'Backup::delete/$1');
    $routes->post('backup/restore/(:segment)', 'Backup::restore/$1');
    $routes->post('backup/cleanup', 'Backup::cleanup');
    $routes->post('backup/prunedata', 'Backup::pruneData');

    // Generate Slip & Laporan
    $routes->get('slip', 'Slip::index');
    $routes->get('slip/create', 'Slip::create');
    $routes->post('slip/store', 'Slip::store');
    $routes->get('slip/edit/(:num)', 'Slip::edit/$1');
    $routes->post('slip/update/(:num)', 'Slip::update/$1');
    
    $routes->post('slip/delete/(:num)', 'Slip::delete/$1');

    $routes->get('generate-slip', 'GenerateSlip::index');
    $routes->get('generate-slip/view/(:num)', 'GenerateSlip::single/$1');
    $routes->get('generate-slip/download/(:num)', 'GenerateSlip::download/$1');
    $routes->get('generate-slip/bulk', 'GenerateSlip::bulk');
    
    $routes->get('import-sipd', 'ImportSipd::index');
    $routes->post('import-sipd', 'ImportSipd::upload');
    $routes->get('laporan', 'Laporan::index');
});

// === KHUSUS BENDAHARA ===
$routes->group('bendahara', ['filter' => 'bendahara'], function($routes) {
    $routes->match(['get', 'post'], 'dashboard', 'Bendahara::dashboard');
    $routes->match(['get', 'post'], 'verifikasi', 'Bendahara::verifikasi');
    $routes->match(['get', 'post'], 'approval', 'Bendahara::approval');
    $routes->match(['get', 'post'], 'finalisasi', 'Bendahara::finalisasi');
    $routes->get('finalisasi/detail/(:num)', 'Bendahara::detail/$1');
    $routes->get('finalisasi/export-pdf/(:num)', 'Bendahara::exportPdfRekap/$1');
    $routes->post('bayar', 'Bendahara::bayar');
    $routes->match(['get', 'post'], 'histori', 'Bendahara::histori');
    $routes->get('preview-slip/(:num)', 'Bendahara::previewSlip/$1');
});

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}