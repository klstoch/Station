<?php

namespace Station\Tool;

final class Compressor extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::compressor;
    }

    public function On(): void
    {
        $this->logger->log('Накачиваем колесо');
        $this->time->wait(seconds: 30);

    }

}