<?php

declare(strict_types=1);

use Acme\Application\Controller\Cart\CartCreateController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return static function (App $app): void {
    $app
        ->group('/cart', function (RouteCollectorProxyInterface $app): void {
            $app->post('', CartCreateController::class);
        });
};
