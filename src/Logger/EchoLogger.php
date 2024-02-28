<?php

declare(strict_types=1);

namespace Station\Logger;

class EchoLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo $message . PHP_EOL;
    }
}