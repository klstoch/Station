<?php

declare(strict_types=1);

use Station\ClientQueue;
use Station\ClientTraffic;
use Station\Employ\LevelSkillEnum;
use Station\Employ\SlidingGraphWork;
use Station\Employ\TyreMechanic;
use Station\Inventory;
use Station\Tool\AirGun;
use Station\Tool\TireChangingMachine;
use Station\Work\TyreReplacement;
use Station\Work\WorkEnum;
use Station\Tool\BalancingMachine;
use Station\Work\WheelBalancing;
use Station\Tool\Compressor;
use Station\Time\VirtualTime;
use Station\Logger\LoggerWithTiming;
use Station\Logger\EchoLogger;
use Station\Work\WheelReplacementBalancing;

require_once __DIR__ . '/vendor/autoload.php';
$time = new VirtualTime(microtime(true), new DateTimeImmutable('2024-02-28 00:00'), 3600);
$logger = new LoggerWithTiming($time, new EchoLogger());

$inventory = new Inventory($logger);
$inventory->addNew(new Compressor($time, $logger));
$inventory->addNew(new AirGun($time, $logger));
$inventory->addNew(new TireChangingMachine($time, $logger));
$inventory->addNew(new BalancingMachine($time, $logger));

/** @var array<TyreMechanic> $employees */

$employees = [
    new TyreMechanic(LevelSkillEnum::starting, 'Петрович', $logger, $inventory, new SlidingGraphWork(3, 3), $time),
    new TyreMechanic(LevelSkillEnum::medium, 'Саныч', $logger, $inventory, new SlidingGraphWork(3, 3, firstWorkDay: new \DateTimeImmutable('2024-03-02')), $time),
];

$clientTraffic = new ClientTraffic();
$clientQueue = new ClientQueue();

while (true) {
    $client = $clientTraffic->getClient();
    $currentTime = $time->current()->format('H:i');
    if ($currentTime >= '08:00' && $currentTime < '20:00' && $clientQueue->isEmptyClientQueue()) {
        $clientQueue->add($client);
    } elseif ($client->agreeToWait()) {
        $clientQueue->add($client);
    }

    $work = match ($client->createWorkName()) {
        WorkEnum::tireReplacement => new TyreReplacement($time, $logger,),
        WorkEnum::wheelBalancing => new WheelBalancing($time, $logger),
        WorkEnum::wheelReplacementBalancing => new WheelReplacementBalancing($time, $logger),
        //default => new TireReplacement($time, $logger), - дефолта пока нет, но он будет, поэтому нужно будет исключение
    };

    foreach ($employees as $employee) {
        if ($employee->canExecute($work) === true){
            $employee->do($work);
        }

    }
    $clientQueue->get();

}




