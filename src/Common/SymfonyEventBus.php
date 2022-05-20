<?php

declare(strict_types=1);

namespace App\Common;

use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyEventBus implements EventBus
{
    public function __construct(private readonly MessageBusInterface $eventBus)
    {
    }

    public function dispatch(DomainEvent $domainEvent): void
    {
        $this->eventBus->dispatch($domainEvent);
    }
}
