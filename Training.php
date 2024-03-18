<?php



require_once __DIR__.'/vendor/autoload.php';

$virtualTime = new \Station\Time\VirtualTime(microtime(true), new DateTimeImmutable());
$logger = new \Station\Logger\LoggerWithTiming($virtualTime, new \Station\Logger\EchoLogger(),);

$redis = new \Redis();
$redis->connect('127.0.0.1');
$mutex = new Station\Mutex\Mutex($redis, 'RedisBasedInventory');
$obj = new \Station\Inventory\RedisBasedInventory($logger, $redis, $mutex);



$obj1 = new \Station\Queue\RedisBasedClientQueue( $redis, $mutex);

readline();
//$obj->addNew(new \Station\Tool\AirGun($virtualTime, $logger));

$client = new \Station\Client\ClientTraffic();
$obj1->add($client->getClient());