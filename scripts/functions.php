<?php

declare(strict_types=1);

use Station\Employ\EmployeeRepository;
use Station\PilotStation\Station;
use Station\Employ\EmployInterface;
use Station\Infrastructure\IO\IOInterface;
use Station\PilotStation\StationRepository;

require_once __DIR__.'/../vendor/autoload.php';

function selectEmploy(EmployeeRepository $employeeRepository, IOInterface $io): ?EmployInterface
{
    $namesForSelect = array_map(
        static fn (EmployInterface $employee) => sprintf('%s (%s)', $employee->getName(), $employee->getId()),
        $employeeRepository->getAll(),
    );
    $input = $io->requestInput('Выбери ФИО сотрудника', $namesForSelect);
    $id = findById($input);

    return $employeeRepository->get($id);

}

function selectStation(IOInterface $io, StationRepository $stationRepository): Station
{
    $stations = $stationRepository->getAll();
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