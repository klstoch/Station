<?php

declare(strict_types=1);

use Station\Client\Client;
use Station\PilotStation\Station;
use Station\Employ\EmployInterface;
use Station\Infrastructure\IO\IOInterface;

require_once __DIR__.'/../vendor/autoload.php';

function selectClient(IOInterface $io, Redis $redis): ?Client
{
    $station = selectStation($io, $redis);
    $listClients = function (Client $client) {
        return sprintf('%s (%s)', $client->getName(), $client->getId());
    };
    $namesForSelect = array_map($listClients, $station->getClients());
    $input = $io->requestInput('Выбери ФИО клиента', $namesForSelect);
    if (preg_match("~\(.*\)~", $input, $matches) !== false) {
        $id = trim($matches[0], '()');
    } else {
        throw new Exception('Неверный ввод');
    }
    foreach ($station->getClients() as $client) {
        if ($id === $client->getId()) {
            return $client;
        }
    }
    return null;
}
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