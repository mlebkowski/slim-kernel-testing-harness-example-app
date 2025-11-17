<?php

declare(strict_types=1);

namespace Acme\Application\Kernel;

use PDO;
use Psr\Container\ContainerInterface;
use WonderNetwork\SlimKernel\ServicesBuilder;
use WonderNetwork\SlimKernel\StartupHook;

/**
 * I couldn’t be bothered to wire doctrine here, I’m short on time
 */
final readonly class DatabaseSchemaStartupHook implements StartupHook {
    public function __invoke(ServicesBuilder $builder, ContainerInterface $container): void {
        $pdo = $container->get(PDO::class);

        $pdo->exec(
            <<<SQL
            CREATE TABLE `product` (
                `id` text unique not null, 
                `name` text unique not null,
                `price` integer not null
            ) 
            SQL,
        );

        $pdo->exec(
            <<<SQL
            CREATE TABLE `cart` (
                `id` integer auto_increment primary key,
                `user_id` char(32) not null
            )
            SQL,
        );

        $pdo->exec(
            <<<SQL
            CREATE TABLE `cart_product` (
                `id` integer auto_increment primary key,
                `user_id` char(32) not null
            )
            SQL,
        );
    }
}
