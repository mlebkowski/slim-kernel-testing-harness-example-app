<?php

declare(strict_types=1);

use Acme\Application\Controller\Cart\CartAddItemController;
use Acme\Application\Controller\Cart\CartCreateController;
use Acme\Application\Controller\Cart\CartGetController;
use Acme\Application\Controller\Cart\CartRemoveItemController;
use Acme\Application\Controller\Cart\UserCartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return static function (App $app): void {
    $app
        ->group('/cart', function (RouteCollectorProxyInterface $app): void {
            $app->post('', CartCreateController::class);
            $app
                ->group('/{id}', function (RouteCollectorProxyInterface $app) {
                    $app->get('', CartGetController::class);
                    $app->post('/item', CartAddItemController::class);
                    $app->delete('/item', CartRemoveItemController::class);
                })
                ->add(UserCartMiddleware::class);
        });
};
