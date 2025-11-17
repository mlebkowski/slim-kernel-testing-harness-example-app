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

        $repository->save(Product::new(name: "Fallout", price: 199));
        $repository->save(Product::new(name: "Don’t Starve", price: 299));
        $repository->save(Product::new(name: "Baldur’s Gate", price: 399));
        $repository->save(Product::new(name: "Icewind Dale", price: 499));
        $repository->save(Product::new(name: "Bloodborne", price: 599));
    }
}
