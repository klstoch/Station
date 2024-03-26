<?php

declare(strict_types=1);

use Station\BaseClient\ClientBaseRepository;
use Station\Client\ClientTraffic;
use Station\Infrastructure\IO\IOFactory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Mutex\Mutex;
use Station\PilotStation\StationRepository;
/*use Station\Work\TyreReplacement;
use Station\Work\WheelBalancing;
use Station\Work\WheelReplacementBalancing;*/


require_once __DIR__.'/functions.php';
$redis = new \Station\Infrastructure\Cache\Redis();

$stationRepository = new StationRepository($redis, new Mutex($redis));
$clientBaseRepository = new ClientBaseRepository($redis);
$ioFactory = new IOFactory();
$io = $ioFactory->create();

$clientTraffic = new ClientTraffic();


$station = selectStation($io, $stationRepository);
$clientQueue = $station->getClientQueue();
$clientBase = $station->getClientBase();
$time = $station->getTime();
$logger = new LoggerWithTiming($time, new EchoLogger());

$clientsAllocation = random_int(0, 9);

while(true){
    if ($clientsAllocation > 2 || empty($clientBaseRepository->getAll())) {
        $client = $clientTraffic->getClient();
        $clientBase->add($client);
    } else {
        $client = $clientBase->get();

    }
    if ($station->getGraphWork()->isWorkTime($time->current()) && $clientQueue->isEmptyClientQueue()) {
        $clientQueue->add($client);
        $logger->log('приехал ' . $client->getName() . ', всего ' . $clientQueue->count());
    } elseif ($client->agreeToWait()) {
        $clientQueue->add($client);
        $logger->log('приехал ' . $client->getName() . ', всего ' . $clientQueue->count());
    }

    $time->wait(minute: random_int(30, 90));
}
