<?php

declare(strict_types=1);

namespace Station\Infrastructure\Logger;

interface LoggerInterface
{
    public function log(string $message): void;
}