<?php

return static function (\App\Controller\CopieExamenController $controller): void {
    \Steampixel\Route::add('/copies/create', [$controller, 'formulaire'], 'get');
    \Steampixel\Route::add('/copies', [$controller, 'liste'], 'get');
    \Steampixel\Route::add('/copies', [$controller, 'soumettre'], 'post');
    \Steampixel\Route::add(
        '/copies/([0-9]+)',
        static function (string $id) use ($controller): void {
            $controller->detail((int) $id);
        },
        'get',
    );
};
