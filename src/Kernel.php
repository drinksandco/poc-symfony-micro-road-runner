<?php

declare(strict_types=1);

namespace App;

use App\Controller\TestController;
use Baldinof\RoadRunnerBundle\BaldinofRoadRunnerBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new BaldinofRoadRunnerBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        // PHP equivalent of config/packages/framework.yaml
        $container->extension('framework', [
            'secret' => 'S0ME_SECRET'
        ]);
        $container->extension('baldinof_road_runner', [
            'kernel_reboot' => [
                'strategy' => 'on_exception',
                'allowed_exceptions' => [
                    \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface::class,
                    \Symfony\Contracts\HttpClient\Exception\ExceptionInterface::class,
                ]
            ],
            'metrics' => [
                'enabled' => false,
            ]
        ]);
        $container->services()
            ->load('App\\', '../src')
            ->autoconfigure(true)
            ->autowire(true);
        ;
        $container->services()
            ->load('App\\Controller\\', '../src/Controller')
            ->autoconfigure(true)
            ->autowire(true)
            ->tag('controller.service_arguments');
        ;
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('external_api_call', '/')->controller(TestController::class);
    }
}
    