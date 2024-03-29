<?php

declare(strict_types=1);

namespace Station\Tool;

final class TireChangingMachine extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::tireChangingMachine;
    }

    public function putOff(): void
    {
        $this->logger->log('Снимаем покрышку с колеса');
        $this->time->wait(minute: 5);
    }

    public function putOn(): void
    {
        $this->logger->log('Одеваем покрышку на колесо');
        $this->time->wait(minute: 5);

    }
}
