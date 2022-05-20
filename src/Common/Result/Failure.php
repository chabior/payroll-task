<?php

declare(strict_types=1);

namespace App\Common\Result;

use App\Common\Result;

final class Failure extends Result
{
    public function __construct(protected readonly string $reason)
    {
    }
}
