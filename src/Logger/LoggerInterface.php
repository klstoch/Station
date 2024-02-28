<?php

declare(strict_types=1);

namespace Station\Logger;

interface LoggerInterface
{
    public function log(string $message): void;
}