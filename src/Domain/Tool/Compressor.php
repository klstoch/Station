<?php

namespace Station\Domain\Tool;

use Doctrine\ORM\Mapping\Entity;
use Station\Domain\Action;
use Station\Domain\Time\Duration;

#[Entity]
final class Compressor extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::compressor;
    }

    public function pump(): Action
    {
        return new Action('Накачиваем колесо', Duration::s(30));
    }

    public function deflate(): Action
    {
        return new Action('Сдуваем колесо', Duration::s(30));
    }
}