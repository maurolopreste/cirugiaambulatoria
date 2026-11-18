<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Las credenciales NO se versionan: se leen de application/config/secrets.php o de variables de entorno
// (ver secrets.example.php). En desarrollo se usa el modo sandbox con credenciales de prueba.
$_mp = function ($key) { return function_exists('_secret') ? _secret($key) : getenv($key); };

$config['mode'] = (ENVIRONMENT === 'production') ? 'production' : 'sandbox';

$config['ci'] = $_mp('MP_CLIENT_ID');
$config['cs'] = $_mp('MP_CLIENT_SECRET');

// Modo sandbox
$config['public_key_sandbox']   = $_mp('MP_PUBLIC_KEY');
$config['access_token_sandbox'] = $_mp('MP_ACCESS_TOKEN');

// Modo producción
$config['public_key_production']   = $_mp('MP_PUBLIC_KEY');
$config['access_token_production'] = $_mp('MP_ACCESS_TOKEN');
