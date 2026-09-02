<?php

return static function (\App\Controller\CopieExamenController $controller): void {
    \Steampixel\Route::add('/copies/create', [$controller, 'formulaire'], 'get');
    \Steampixel\Route::add('/copies', [$controller, 'liste'], 'get');
    \Steampixel\Route::add('/copies', [$controller, 'soumettre'], 'post');
};
