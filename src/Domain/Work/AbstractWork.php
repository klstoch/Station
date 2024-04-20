<?php

namespace Station\Domain\Work;

use Station\Domain\Action;
use Station\Domain\Event\EventDispatcherInterface;
use Station\Domain\Station\Employ\EmployInterface;

abstract class AbstractWork implements WorkInterface
{
    protected EmployInterface $employ;
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            TyreReplacement::name(),
            WheelBalancing::name(),
            WheelReplacementBalancing::name(),
        ];
    }

    public function execute(EmployInterface $employ, EventDispatcherInterface $eventDispatcher): void
    {
        $this->employ = $employ;
        $this->eventDispatcher = $eventDispatcher;

        $this->doExecute();
    }

    abstract protected function doExecute(): void;

    protected function doAction(Action $action): void
    {
        $this->employ->doAction($action, $this->eventDispatcher);
    }
}