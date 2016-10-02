<?php
$route['Auth'] = 'Auth/Login';
$route['Auth/Logout'] = 'Auth/Login/logout';
$route['Auth/Reset/(:any)/(:any)'] = 'Auth/Reset/index/$1/$2';
