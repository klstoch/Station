<?php

declare(strict_types=1);

namespace Station\Tool;

final class AirGun extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::airGun;
    }

    public function spinLeft(): void
    {
        $this->logger->log('Откручиваем гайку');
        $this->time->wait(minute: 1);

    }

    public function spinRight(): void
    {
        $this->logger->log('Закручиваем гайку');
        $this->time->wait(minute: 1);

    }
}
