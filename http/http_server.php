<?php

$http = new Swoole\Http\Server("0.0.0.0", 9501);

$http->on("Request", function(Swoole\Http\Request $request,Swoole\Http\Response $response) {

    var_dump($request);
    $response->header("Content-Type", "text/html;charset=utf-8");
    $response->end("<h1>Hello world</h1>");
});

$http->start();