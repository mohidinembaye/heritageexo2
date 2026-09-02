<?php


require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/Database.php';

// Chargement du .env une seule fois, au point d'entrée unique
$env = \App\Config\chargerEnv(dirname(__DIR__) . '/.env');
$_ENV = array_merge($_ENV, $env);
