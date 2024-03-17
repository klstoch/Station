<?php

declare(strict_types=1);

use Station\Company_Station\Station;
use Station\Employ\EmployInterface;
use Station\Infrastructure\IO\IOInterface;

require_once __DIR__.'/../vendor/autoload.php';

function selectEmploy(IOInterface $io, Redis $redis): ?EmployInterface
{
    $station = selectStation($io, $redis);
    $listEmployees = function (EmployInterface $employee) {
        return sprintf('%s (%s)', $employee->getName(), $employee->getId());
    };
    $namesForSelect = array_map($listEmployees, $station->getEmployees());
    $input = $io->requestInput('Выбери ФИО сотрудника', $namesForSelect);
    if (preg_match("~\(.*\)~", $input, $matches) !== false) {
        $id = trim($matches[0], '()');
    } else {
        throw new Exception('Неверный ввод');
    }
    foreach ($station->getEmployees() as $employee) {
        if ($id === $employee->getId()) {
            return $employee;
        }
    }
    return null;
}

function selectStation(IOInterface $io, Redis $redis): Station
{
    $stations = $redis->hGetAll('stations');
    $listStation = function (string $serializedStation) {
        /** @var Station $station */
        $station = unserialize($serializedStation, ['allowed_classes' => true]);
        return sprintf('%s (%s)', $station->getName(), $station->getId());
    };
    $namesForSelect = array_map($listStation, $stations);
    $input = $io->requestInput('Выбери имя станции', array_values($namesForSelect));
    if (preg_match("~\(.*\)~", $input, $matches) !== false) {
        $id = trim($matches[0], '()');
    } else {
        throw new Exception('Неверный ввод');
    }

    $stationSerialized = $redis->hGet('stations', $id);
    /** @var Station $station */
    return unserialize($stationSerialized, ['allowed_classes' => true]);
}