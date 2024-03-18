<?php
namespace Station\Infrastructure;
enum EnumDayOfWeek: int
{
    case monday = 1;
    case tuesday = 2;
    case wednesday = 3;
    case thursday = 4;
    case friday = 5;
    case saturday = 6;
    case sunday = 7;

    public function title(): string //
    {
        return match (true) {
            $this === self::monday => 'понедельник',
            $this === self::tuesday => 'вторник',
            $this === self::wednesday => 'среда',
            $this === self::thursday => 'четверг',
            $this === self::friday => 'пятница',
            $this === self::saturday => 'суббота',
            $this === self::sunday => 'воскресенье',
        };
    }

}