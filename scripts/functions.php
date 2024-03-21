<?php

declare(strict_types=1);

use Station\Client\Client;
use Station\PilotStation\Station;
use Station\Employ\EmployInterface;
use Station\Infrastructure\IO\IOInterface;
use Station\PilotStation\StationRepository;

require_once __DIR__.'/../vendor/autoload.php';

function selectEmploy(Station $station, IOInterface $io): ?EmployInterface
{
    $namesForSelect = array_map(
        static fn (EmployInterface $employee) => sprintf('%s (%s)', $employee->getName(), $employee->getId()),
        $station->getEmployees(),
    );
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

function selectStation(IOInterface $io, StationRepository $stationRepository): Station
{
    $stations = $stationRepository->getAll();
    $namesForSelect = array_map(
        static fn (Station $station) => sprintf('%s (%s)', $station->getName(), $station->getId()),
        $stations,
    );
    $input = $io->requestInput('Выбери имя станции', array_values($namesForSelect));
    if (preg_match("~\(.*\)~", $input, $matches) !== false) {
        $id = trim($matches[0], '()');
    } else {
        throw new Exception('Неверный ввод');
    }

    return $stationRepository->get($id);

}