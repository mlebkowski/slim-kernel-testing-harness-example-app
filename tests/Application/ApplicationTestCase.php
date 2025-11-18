<?php

declare(strict_types=1);

namespace Acme\Application;

use Acme\Application\Authorization\Role;
use Acme\Application\Kernel\ContainerFactory;
use Acme\Application\Kernel\Environment;
use Acme\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Slim\App;
use WonderNetwork\SlimKernelTestingHarness\KernelHttpClient\SlimKernelHttpClient;

class ApplicationTestCase extends TestCase {
    private App $app;

    protected function setUp(): void {
        $container = ContainerFactory::build(Environment::Test);
        $this->app = $container->get(App::class);
    }

    protected function cartUseCase(?User $user): CartUseCasesFacade {
        return CartUseCasesFacade::of(
            httpClient: SlimKernelHttpClient::create($this->app)
                ->withMiddleware(UserMiddleware::of($user)),
        );
    }

    protected function productUseCase(?Role $role): ProductUseCasesFacade {
        return ProductUseCasesFacade::of(
            httpClient: SlimKernelHttpClient::create($this->app)
                ->withMiddleware(RoleMiddleware::of($role)),
        );
    }
}
