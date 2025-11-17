<?php

declare(strict_types=1);

namespace Acme\Application\Kernel;

use Acme\Domain\Product\Product;
use Acme\Domain\Product\ProductRepository;
use Psr\Container\ContainerInterface;
use WonderNetwork\SlimKernel\ServicesBuilder;
use WonderNetwork\SlimKernel\StartupHook;

final readonly class ProductFixturesStartupHook implements StartupHook {
    public function __invoke(ServicesBuilder $builder, ContainerInterface $container): void {
        $repository = $container->get(ProductRepository::class);

        $repository->save(Product::new(name: "Fallout", price: 1.99));
        $repository->save(Product::new(name: "Don’t Starve", price: 2.99));
        $repository->save(Product::new(name: "Baldur’s Gate", price: 3.99));
        $repository->save(Product::new(name: "Icewind Dale", price: 4.99));
        $repository->save(Product::new(name: "Bloodborne", price: 5.99));
    }
}
