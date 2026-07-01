<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'auth' => \App\Filters\AuthFilter::class,
		'admin' => \App\Filters\AdminFilter::class,
		'bendahara' => \App\Filters\BendaharaFilter::class,
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			// 'csrf' is handled via forms with csrf_field() or specifically mapped to POST
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [
		// CSRF dipindah ke $filters agar rute login tidak ikut ter-blokir
	];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
		// CSRF hanya berlaku untuk rute terautentikasi (bukan login)
		'csrf' => ['before' => [
			'dashboard',
			'dashboard/*',
			'pegawai',
			'pegawai/*',
			'backup',
			'backup/*',
			'slip',
			'slip/*',
			'generate-slip',
			'generate-slip/*',
			'import-sipd',
			'import-sipd/*',
			'laporan',
			'laporan/*',
			'bendahara/*',
		]],
	];
}

