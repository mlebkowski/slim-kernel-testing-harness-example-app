<?php

declare(strict_types=1);

namespace Acme\Application\Kernel;

use Psr\Container\ContainerInterface;
use WonderNetwork\SlimKernel\KernelBuilder;
use WonderNetwork\SlimKernel\SlimExtension\ErrorMiddlewareConfiguration;
use WonderNetwork\SlimKernel\StartupHook\RoutesStartupHook;

final readonly class ContainerFactory {
    public function build(Environment $environment): ContainerInterface {
        $envName = $environment->value;

        return KernelBuilder::start(dirname(__DIR__, 3))
            ->add([
                ErrorMiddlewareConfiguration::class => $environment->displayErrors()
                    ? ErrorMiddlewareConfiguration::verbose()
                    : ErrorMiddlewareConfiguration::silent(),
            ])
            ->onStartup(
                new RoutesStartupHook('app/routes/*.php'),
                new DatabaseSchemaStartupHook(),
                new ProductFixturesStartupHook(),
            )
            ->glob(
                'app/services/*.php',
                "app/services/{$envName}/*.php",
            )
            ->build();
    }
}
