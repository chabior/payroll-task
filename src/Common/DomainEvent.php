<?php

declare(strict_types=1);

namespace Dna\OnlineCourses\Shared\Common;

interface DomainEvent
{
    public function eventId(): UUID;
}
