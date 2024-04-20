<?php

declare(strict_types=1);

namespace Station\Infrastructure\Logger;

class EchoLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo $message . PHP_EOL;
    }
}