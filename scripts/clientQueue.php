<?php

declare(strict_types=1);

use Station\Client\ClientTraffic;
use Station\Infrastructure\IO\IOFactory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Mutex\Mutex;
use Station\PilotStation\StationRepository;
use Station\Queue\RedisBasedClientQueue;
use Station\Time\VirtualTime;
/*use Station\Work\TyreReplacement;
use Station\Work\WheelBalancing;
use Station\Work\WheelReplacementBalancing;*/

//$time = new VirtualTime(microtime(true), new DateTimeImmutable('2024-03-09 08:00'), 60);

require_once __DIR__.'/functions.php';
$redis = new \Redis();
$redis->connect('127.0.0.1');

$stationRepository = new StationRepository($redis, new Mutex($redis));

$ioFactory = new IOFactory();
$io = $ioFactory->create();

$clientTraffic = new ClientTraffic();


$station = selectStation($io, $stationRepository);
$clientQueue = $station->getClientQueue();
$time = $station->getTime();
$logger = new LoggerWithTiming($time, new EchoLogger());

$clientsAllocation = random_int(0, 9);

while(true){
    if ($clientsAllocation > 2 || empty($station->getClients())) {
        $client = $clientTraffic->getClient();
        $station->addClient($client);
        $redis->hSet('stations', $station->getId(), serialize($station));
    } else {
        $clients = $station->getClients();
        $client = $clients[random_int(0, count($clients)-1)];
    }
    if ($station->getGraphWork()->isWorkTime($time->current()) && $clientQueue->isEmptyClientQueue()) { // время работы должно соответствовать выбранному пользователем в управлении станцией
        $clientQueue->add($client);
        $logger->log('приехал ' . $client->getName());
    } elseif ($client->agreeToWait()) {
        $clientQueue->add($client);
        $logger->log('приехал ' . $client->getName());
    }

    $time->wait(minute: 10);
}
