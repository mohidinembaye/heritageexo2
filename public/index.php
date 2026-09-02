<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$repository = new \App\Repository\CopieExamenRepository(
	\App\Repository\Database::connect(),
);
$service = new \App\Service\SoumissionCopieService(
	new \App\Service\CalculNoteAvecRetardService(),
	$repository,
);
$controller = new \App\Controller\CopieExamenController($service, $repository);

$enregistrerRoutes = require dirname(__DIR__) . '/config/routes.php';
$enregistrerRoutes($controller);

\Steampixel\Route::run('/');

