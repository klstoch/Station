<?php

declare(strict_types=1);

namespace Station\Tool;

use Station\PilotStation\GeneratorID;
use Station\Employ\EmployInterface;
use Station\Logger\LoggerInterface;
use Station\Time\VirtualTime;

abstract class AbstractTool implements ToolInterface
{
    private readonly string $id;

    public function __construct(
        protected VirtualTime     $time,
        protected LoggerInterface $logger,
    ) {
      $this->id = GeneratorID::genID();
    }

    private bool $isBusy = false;

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function take(EmployInterface $employ): void
    {
        $this->logger->log($employ->getName() . ' принял в работу ' . $this::name()->value);
        $this->isBusy = true;
    }

    public function put(EmployInterface $employ): void
    {
        $this->logger->log($employ->getName() . ' освободил ' . $this::name()->value);
        $this->isBusy = false;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
