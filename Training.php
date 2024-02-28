<?php

require_once __DIR__.'/vendor/autoload.php';

$obj = new \Station\ClientTraffic();
$client1 = $obj->getClient();
$client2 = $obj->getClient();
$client3 = $obj->getClient();

$i=1;