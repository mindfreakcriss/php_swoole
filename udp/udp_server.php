<?php

$server = new \Swoole\Server("127.0.0.1", 9501, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//监听数据接收事件
$server->on("Packet", function($server, $data, $clientInfo) {
    var_dump($clientInfo);
    $server->sendto($clientInfo['address'], $clientInfo['port'], "server : {$data}"); //给客户端发送数据
});

$server->start();