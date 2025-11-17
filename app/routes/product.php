<?php

declare(strict_types=1);

use Acme\Application\Authorization\RequireAdminRoleMiddleware;
use Acme\Application\Controller\ProductAddController;
use Acme\Application\Controller\ProductListController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return static function (App $app): void {
    $app
        ->group('/product', function (RouteCollectorProxyInterface $app): void {
            $app->get('', ProductListController::class);
            $app->post('', ProductAddController::class);
        })
        ->add(RequireAdminRoleMiddleware::class);
};
