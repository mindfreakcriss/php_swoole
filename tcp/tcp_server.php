<?php

//创建server 对象，监听端口
$server = new Swoole\Server("127.0.0.1", 9501);

//执行异步任务
$server->set([
    'task_worker_num' => 4
]);


//监听连接进入事件,这个 $fd 由系统自动分配，可以直接使用，进程ID
$server->on("Connect", function($server, $fd) {
    echo "Client: Connect." . $fd . "\n";
});

//监听数据接收事件
$server->on("Receive", function($server, $fd, $reactor_id, $data) {
    echo "接收到fd:{$fd}的数据, ReactorId 为 {$reactor_id}\n";

    //投递任务
    $task_id = $server->task($data);

    echo "Dispatch AsyncTask : id={$task_id}\n";

   ///  $server->send($fd, "Server : {$data}");
   // $server->close($fd); //主动关闭客户端连接
});

//监听连接关闭事件，客户端可能触发
$server->on("Close", function($server, $fd) {
    echo "Client {$fd}: Close.\n";
});

//处理异步任务，此回调函数在 task 进程中执行
$server->on("Task", function($server, $task_id, $reactor_id, $data) {
    echo "New AsyncTask[id={$task_id}]".PHP_EOL;
    //返回任务执行的结果
    $server->finish("{$data} -> OK");
});

//处理异步任务的结果(此回调函数在worker进程中执行)
$server->on('Finish', function ($serv, $task_id, $data) {
    echo "AsyncTask[{$task_id}] Finish: {$data}".PHP_EOL;
});

//启动服务器
$server->start();