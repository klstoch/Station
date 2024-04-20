<?php

use Station\Infrastructure\Doctrine\Type\CarType;
use Station\Infrastructure\Doctrine\Type\CompetenceListType;
use Station\Infrastructure\Doctrine\Type\GradeType;
use Station\Infrastructure\Doctrine\Type\ScheduleType;
use Station\Infrastructure\Doctrine\Type\VirtualTimeType;

return [
    'paths' => [
        dirname(__DIR__) . '/src/',
    ],
    'is_dev_mode' => true,
    'types' => [
        ScheduleType::NAME => ScheduleType::class,
        GradeType::NAME => GradeType::class,
        VirtualTimeType::NAME => VirtualTimeType::class,
        CarType::NAME => CarType::class,
        CompetenceListType::NAME => CompetenceListType::class,
    ],
];