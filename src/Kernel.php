<?php

namespace App;

use App\Common\EventHandler;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->registerForAutoconfiguration(EventHandler::class)
            ->addTag(
                'messenger.message_handler',
                [
                    'bus' => 'event.bus',
                ]
            );
    }
}
