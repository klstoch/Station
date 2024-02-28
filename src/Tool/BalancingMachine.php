<?php

namespace Station\Tool;

final class BalancingMachine extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::balancingMachine;
    }
    public function setUpBalance(): void
    {
        $this->logger->log('Устанавливаем колесо на балансир');
        $this->time->wait(seconds: 30);

    }
    public function pulOffBalance(): void
    {
        $this->logger->log('Снимаем колесо с балансира');
        $this->time->wait(seconds: 30);
    }
}