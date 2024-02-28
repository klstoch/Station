<?php

declare(strict_types=1);

namespace Station\Tool;

use Station\Employ\EmployInterface;
use Station\Logger\LoggerInterface;
use Station\Logger\LoggerWithTiming;
use Station\Time\VirtualTime;

abstract class AbstractTool implements ToolInterface
{
    public function __construct(protected VirtualTime $time, protected LoggerInterface $logger)
    {

    }
    private bool $isBusy = false;

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function take(EmployInterface $employ): void
    {
        $this->logger->log($employ->name() . ' принял в работу ' . $this::name()->value);
        $this->isBusy = true;
    }

    public function put(EmployInterface $employ): void
    {
        $this->logger->log($employ->name() . ' освободил ' . $this::name()->value);
        $this->isBusy = false;
    }
}
