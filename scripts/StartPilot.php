<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';



use Station\Infrastructure\IO\IOFactory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Mutex\Mutex;
use Station\PilotStation\RedisBasedStationRepository;
use Station\Queue\RedisBasedClientQueueFactory;
use Station\Work\TyreReplacement;
use Station\Work\WheelBalancing;
use Station\Work\WheelReplacementBalancing;

$redis = new \Station\Infrastructure\Cache\Redis();

$stationRepository = new RedisBasedStationRepository($redis, new Mutex($redis));

$ioFactory = new IOFactory();
$io = $ioFactory->create();


$station = selectStation($io, $stationRepository);
$employee = selectEmploy($station, $io);
$time = $station->getTime();
$logger = new LoggerWithTiming($time, new EchoLogger());
$queueFactory = new RedisBasedClientQueueFactory($redis);

while (true) {
    $client = $station->getClientQueue($queueFactory)->get();
    if ($client === null) {
        $time->wait(minute: 1);
        continue;
    }

    $work = match ($client->requestForTypeWork()) {
        TyreReplacement::name() => new TyreReplacement($time, $logger,),
        WheelBalancing::name() => new WheelBalancing($time, $logger),
        WheelReplacementBalancing::name() => new WheelReplacementBalancing($time, $logger),
    };

    while (true) {
        if (!$employee->isWorkTime()) {
            continue;
        }

        if ($employee->canExecute($work)) {
            $employee->do($work);
            break;
        }

        $employee->doBreak();
    }
}