<?php

declare(strict_types=1);

use Acme\Application\Authorization\RequireAdminRoleMiddleware;
use Acme\Application\Controller\ProductAddController;
use Acme\Application\Controller\ProductDeleteController;
use Acme\Application\Controller\ProductListController;
use Acme\Application\Controller\ProductUpdateController;
use Acme\Application\Transaction\ProductTransactionMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return static function (App $app): void {
    $app
        ->group('/product', function (RouteCollectorProxyInterface $app): void {
            $app->get('', ProductListController::class);
            $app->post('', ProductAddController::class);
            $app->group('/{id}', function (RouteCollectorProxyInterface $app): void {
                $app->delete('', ProductDeleteController::class);
                $app->patch('', ProductUpdateController::class);
            });
        })
        ->add(ProductTransactionMiddleware::class)
        ->add(RequireAdminRoleMiddleware::class);
};
