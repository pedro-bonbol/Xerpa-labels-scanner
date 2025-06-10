<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Etiquetas::index');
$routes->get('/review', 'Etiquetas::review');
$routes->post('etiquetas/procesar', 'Etiquetas::procesar');
$routes->get('/labels/standar_label', 'StandarLabel::etiqueta_estandar');


