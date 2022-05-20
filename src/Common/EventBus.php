<?php

declare(strict_types=1);

namespace App\Common;

interface EventBus
{
    public function dispatch(DomainEvent $domainEvent): void;
}
