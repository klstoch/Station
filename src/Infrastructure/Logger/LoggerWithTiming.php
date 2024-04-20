<?php

declare(strict_types=1);

namespace Station\Infrastructure\Logger;

use Station\Domain\Time\VirtualTime;

readonly class LoggerWithTiming implements LoggerInterface
{
    public function __construct(
        private VirtualTime     $virtualTime,
        private LoggerInterface $logger,
        private string          $format = 'Y-m-d H:i:s',
    )
    {
    }

    public function log(string $message): void
    {
        $messageWithTiming = $this->virtualTime->current()->format($this->format) . ' ' . $message;
        $this->logger->log($messageWithTiming);
    }
}