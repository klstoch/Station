<?php

declare(strict_types=1);

use Station\Company_Station\GeneratorID;
use Station\Company_Station\Station;
use Station\Employ\ConstantGraphWork;
use Station\Employ\GraphIntervals;
use Station\Employ\Time;
use Station\Employ\TimeInterval;
use Station\EnumDayOfWeek;
use Station\Infrastructure\IO\IOFactory;
use Station\Inventory\RedisBasedInventory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Mutex\Mutex;
use Station\Time\VirtualTime;
use Station\Tool\AirGun;
use Station\Tool\BalancingMachine;
use Station\Tool\Compressor;
use Station\Tool\TireChangingMachine;
use Station\Tool\ToolEnum;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/Functions.php';
$generatorID = new GeneratorID();
$time = new VirtualTime(microtime(true), new DateTimeImmutable('2024-03-09 08:00'), 60);
$logger = new LoggerWithTiming($time, new EchoLogger());

$ioFactory = new IOFactory();
$io = $ioFactory->create();

$timeStart = ['08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00'];
$timeFinal = ['18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00', '20:15', '20:30', '20:45', '21:00'];

$config = [];
for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
    $dayTitle = EnumDayOfWeek::from($dayOfWeek)->title();
    $config[$dayOfWeek] = new GraphIntervals(
        workingTimeInterval: new TimeInterval(
            Time::from($io->requestInput("Введи время начала работы ($dayTitle)", $timeStart, '08:00')),
            Time::from($io->requestInput("Введи время окончание работы ($dayTitle)", $timeFinal, '20:00')),
        ),
    );
}

$graph = new ConstantGraphWork($config);


$redis = new \Redis();
$redis->connect('127.0.0.1');
$mutex = new Mutex($redis, 'RedisBasedInventory');

$answerByStation = ['Создать новую станцию', 'Внести изменения в существующую станцию'];
$answerByInventory = ['Создать новый инвентарь', 'Использовать существующий'];

$isStationsExists = $redis->hGetAll('stations') !== null;

$isNeedCreate = !$isStationsExists
    || $io->requestInput('Желаете создать новую станцию или внести изменения в существующую? ', $answerByStation, 'Создать новую станцию') === 'Создать новую станцию';

if ($isNeedCreate) {
    $name = readline('Введи название предприятия: ' . PHP_EOL);
    $address = readline('Введи адрес оказания услуг: ' . PHP_EOL);
    $isStationsExists = $redis->hGetAll('stations') !== null;
    $isNeedCreate = !$isStationsExists
        || $io->requestInput('Желаете создать новый инвентарь или использовать существующий? ', $answerByInventory, 'Создать новый инвентарь') === 'Создать новый инвентарь';
    if ($isNeedCreate) {
        $inventory = new RedisBasedInventory($logger, $redis, $mutex);
    } else {
        $inventory = selectStation($io, $redis)->getInventory();
    }
    $station = new Station($name, $address, $graph, $inventory);
    $redis->hSet('stations', $station->getId(), serialize($station));
} else {
    $station = selectStation($io, $redis);
}

while (true) {
    $toolName = $io->requestInput('Выбери инструмент', array_map(static fn(ToolEnum $enum) => $enum->value, ToolEnum::cases()));
    $tool = match ($toolName) {
        ToolEnum::compressor->value => new Compressor($time, $logger),
        ToolEnum::tireChangingMachine->value => new TireChangingMachine($time, $logger),
        ToolEnum::airGun->value => new AirGun($time, $logger),
        ToolEnum::balancingMachine->value => new BalancingMachine($time, $logger)
    };
    $station->getInventory()->addNew($tool);
}



