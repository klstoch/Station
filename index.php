<?php

declare(strict_types=1);

use Station\Client\ClientTraffic;
use Station\Employ\GradeEnum;
use Station\Employ\Graph\SlidingGraphWork;
use Station\Employ\TyreMechanic;
use Station\Inventory\RedisBasedInventory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Queue\RedisBasedClientQueue;
use Station\Time\VirtualTime;
use Station\Tool\AirGun;
use Station\Tool\BalancingMachine;
use Station\Tool\Compressor;
use Station\Tool\TireChangingMachine;
use Station\Work\TyreReplacement;
use Station\Work\WheelBalancing;
use Station\Work\WheelReplacementBalancing;
use Station\Work\WorkEnumRequired;

require_once __DIR__ . '/vendor/autoload.php';
$time = new VirtualTime(microtime(true), new DateTimeImmutable('2024-02-29 08:00'), 60);
$logger = new LoggerWithTiming($time, new EchoLogger());

$inventory = new RedisBasedInventory($logger);

$generatorId = new \Station\PilotStation\GeneratorID();
$inventory->addNew(new Compressor($time, $logger,  $generatorId::genID()));
$inventory->addNew(new AirGun($time, $logger));
$inventory->addNew(new TireChangingMachine($time, $logger));
$inventory->addNew(new BalancingMachine($time, $logger));

/** @var array<TyreMechanic> $employees */

$employees = [
    new TyreMechanic(GradeEnum::starting, 'Петрович', $logger, $inventory, new SlidingGraphWork(3, 3), $time),
    new TyreMechanic(GradeEnum::medium, 'Саныч', $logger, $inventory, new SlidingGraphWork(3, 3, firstWorkDay: new \DateTimeImmutable('2024-03-02')), $time),
];

$clientTraffic = new ClientTraffic();
$clientQueue = new RedisBasedClientQueue();

while (true) {
    $client = $clientTraffic->getClient();
   /* $currentTime = $time->current()->format('H:i');
    if ($currentTime >= '08:00' && $currentTime < '20:00' && $clientQueue->isEmptyClientQueue()) {
        $clientQueue->add($client);
    } elseif ($client->agreeToWait()) {
        $clientQueue->add($client);
    }*/

    $work = match ($client->createWorkName()) {
        WorkEnumRequired::tireReplacement => new TyreReplacement($time, $logger,),
        WorkEnumRequired::wheelBalancing => new WheelBalancing($time, $logger),
        WorkEnumRequired::wheelReplacementBalancing => new WheelReplacementBalancing($time, $logger),
        //default => new TireReplacement($time, $logger), - дефолта пока нет, но он будет, поэтому нужно будет исключение
    };

    foreach ($employees as $employee) {
        if ($employee->canExecute($work) === true){
            $employee->do($work);
        }

    }
    $clientQueue->get();


}




