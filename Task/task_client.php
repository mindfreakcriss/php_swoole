<?php

$client = new \Swoole\Client(SWOOLE_SOCK_TCP);
$client->connect('127.0.0.1', 9501);
$client->send("hello world");
$ret = $client->recv();
var_dump($ret);