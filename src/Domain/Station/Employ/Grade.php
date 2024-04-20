<?php
namespace Station\Domain\Station\Employ;

enum Grade: string
{
    case intern = 'intern';
    case starting = 'starting';
    case medium = 'medium';
    case expert = 'expert';

    public function title(): string
    {
        return match ($this) {
            self::intern => 'Стажёр',
            self::starting => 'Начинающий',
            self::medium => 'Опытный',
            self::expert => 'Эксперт',
        };
    }
}