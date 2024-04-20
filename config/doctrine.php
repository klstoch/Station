<?php

use Station\Infrastructure\Doctrine\Type\CarType;
use Station\Infrastructure\Doctrine\Type\GradeType;
use Station\Infrastructure\Doctrine\Type\GraphWorkType;
use Station\Infrastructure\Doctrine\Type\VirtualTimeType;

return [
    'paths' => [
        dirname(__DIR__) . '/src/Entities',
    ],
    'is_dev_mode' => true,
    'types' => [
        GraphWorkType::NAME => GraphWorkType::class,
        GradeType::NAME => GradeType::class,
        VirtualTimeType::NAME => VirtualTimeType::class,
        CarType::NAME => CarType::class,
        1],
];