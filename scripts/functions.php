<?php

declare(strict_types=1);

use Station\Employ\EmployeeRepository;
use Station\PilotStation\Station;
use Station\Employ\EmployInterface;
use Station\Infrastructure\IO\IOInterface;
use Station\PilotStation\RedisBasedStationRepository;

require_once __DIR__.'/../vendor/autoload.php';

function selectEmploy(Station $station, IOInterface $io): ?EmployInterface
{
    $namesForSelect = array_map(
        static fn (EmployInterface $employee) => sprintf('%s (%s)', $employee->getName(), $employee->getId()),
        $station->getEmployees(),
    );
    $input = $io->requestInput('Выбери ФИО сотрудника', $namesForSelect);
    $id = findById($input);

    foreach ($station->getEmployees() as $employee) {
        if ($employee->getId() === $id) {
            return $employee;
        }
    }
    return null;

}

//function selectStation(IOInterface $io, RedisBasedStationRepository $stationRepository): Station
function selectStation(IOInterface $io, \Station\PilotStation\StationRepository $stationRepository): Station
{
    //$stations = $stationRepository->getAll();
    $stationEntities = $stationRepository->
    $namesForSelect = array_map(
        static fn (Station $station) => sprintf('%s (%s)', $station->getName(), $station->getId()),
        $stations,
    );
    $input = $io->requestInput('Выбери имя станции', array_values($namesForSelect));
    $id = findById($input);

    return $stationRepository->get($id);

}

function findById($input): ?string
{
    if (preg_match("~\(.*\)~", $input, $matches) !== false) {
        return trim($matches[0], '()');
    } else {
        return throw new Exception('Неверный ввод');
    }
}