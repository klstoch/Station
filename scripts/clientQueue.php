<?php

declare(strict_types=1);

use Station\Client\ClientTraffic;
use Station\Infrastructure\IO\IOFactory;
use Station\Mutex\Mutex;
use Station\Queue\RedisBasedClientQueue;
use Station\Time\VirtualTime;

$time = new VirtualTime(microtime(true), new DateTimeImmutable('2024-03-09 08:00'), 60);
//$logger = new LoggerWithTiming($time, new EchoLogger());

$redis = new \Redis();
$redis->connect('127.0.0.1');
$mutex = new Mutex($redis, 'RedisBasedInventory');

$ioFactory = new IOFactory();
$io = $ioFactory->create();

$datetime = new DateTimeImmutable($io->requestInput('Введи время планируемое для посещения (в формате): '));
$clientTraffic = new ClientTraffic();
$clientQueue = new RedisBasedClientQueue($redis, $mutex);

$station = selectStation($io, $redis);
$clientsAllocation = random_int(0, 9);

while(true){
    if ($clientsAllocation > 2) {
        $client = $clientTraffic->getClient();
        $station->addClient($client);
        $redis->hSet('stations', $station->getId(), serialize($station));
    } else {
        $clients = $station->getClients();
        $client = $clients[random_int(0, count($clients)-1)];
    }

    if ($station->getGraphWork()->isWorkTime($datetime) && $clientQueue->isEmptyClientQueue()) { // время работы должно соответствовать выбранному пользователем в управлении станцией
        $clientQueue->add($client);
    } elseif ($client->agreeToWait()) {
        $clientQueue->add($client);
    }
}