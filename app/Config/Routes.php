<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/',      'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::loginPost');
$routes->get('/logout', 'Auth::logout');

// Authenticated routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/dashboard', 'Dashboard::index');

    // Cetak/Print - accessible by all authenticated roles
    $routes->get('print/(:num)', 'Cetak::index/$1');

    // Pegawai routes
    $routes->group('pegawai', ['filter' => 'role:pegawai'], function ($routes) {
        $routes->get('',                    'Pegawai::index');
        $routes->get('create',              'Pegawai::create');
        $routes->post('store',              'Pegawai::store');
        $routes->get('show/(:num)',         'Pegawai::show/$1');
    });

    // Admin routes
    $routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('',                              'Admin::index');
        $routes->get('show/(:num)',                   'Admin::show/$1');
        $routes->post('rincian/add/(:num)',           'Admin::addRincian/$1');
        $routes->get('rincian/delete/(:num)/(:num)',  'Admin::deleteRincian/$1/$2');
        $routes->post('submit/(:num)',                'Admin::submitToDirector/$1');
        $routes->get('arsip',                        'Admin::arsip');
        // Kendaraan
        $routes->get('kendaraan',                    'Admin::kendaraan');
        $routes->post('kendaraan/store',             'Admin::kendaraanStore');
        $routes->get('kendaraan/toggle/(:num)',      'Admin::kendaraanToggle/$1');
        $routes->get('kendaraan/delete/(:num)',      'Admin::kendaraanDelete/$1');
    });

    // Direktur routes
    $routes->group('direktur', ['filter' => 'role:direktur'], function ($routes) {
        $routes->get('',                   'Direktur::index');
        $routes->get('show/(:num)',        'Direktur::show/$1');
        $routes->post('approve/(:num)',    'Direktur::approve/$1');
        $routes->post('reject/(:num)',     'Direktur::reject/$1');
    });

    // Keuangan routes
    $routes->group('keuangan', ['filter' => 'role:keuangan'], function ($routes) {
        $routes->get('',                   'Keuangan::index');
        $routes->get('show/(:num)',        'Keuangan::show/$1');
        $routes->post('complete/(:num)',   'Keuangan::complete/$1');
    });
});
