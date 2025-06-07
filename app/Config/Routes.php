<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/labels/capture', 'Etiquetas::index');
$routes->get('/labels/review', 'Etiquetas::procesar');

