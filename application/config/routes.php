<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'tareas/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = true;
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
