<?php

$server = new Swoole\Server("127.0.0.1", 9501);

$server->set([
    'task_worker_num' => 4,
]);

# 其实时间的参数需要和文档对应并记住
# https://wiki.swoole.com/#/server/events?id=onreceive

#开始
$server->on("Start", function($server) {
    echo "Server is stating\n";
});

# 连接
$server->on("Connect", function($server, $fd, $reactorId) {
    echo sprintf("Fd %s is Connect , ReactorId is %s\n", $fd, $reactorId);
});

#接受数据 TCP
$server->on("Receive", function ($server, $fd, $reactorId, $data) {
    echo sprintf("receive Fb %s data, the data is %s\n", $fd, $data);
});

# 接受数据 UDP
$server->on("Packet", function($server, $data, $clientInfo) {
    var_dump($clientInfo);
    echo sprintf("UDP data is %s\n", $data);
    $server->sendto($clientInfo['address'], $clientInfo['port'], "Server:{$data}");
});

# 任务事件
$server->on("Task", function($server, $workerId, $data) {
    var_dump($data);
    echo sprintf("Task 任务事件 %s\n", $workerId);
});


# Task finish
$server->on("Finish", function($server, $taskId, $data) {
    echo sprintf("Task id %s\n", $taskId);
});

# PipeMessage 收到 $server->sendMessage()
$server->on("PipeMessage", function($server, $workerId, $message) {
    var_dump(sprintf("收到的消息%s\n", $message));
});

# WorkerError
$server->on("WorkerError", function($server, $workerId, $workerPid, $exitCode, $signal) {
    # 工作进程错误
});

# 管理进程启动触发
$server->on("ManagerStart", function($server) {
    echo "Manager process is start\n";
});

# 管理进程结束
$server->on("ManagerStop", function($server) {
    echo "manager process is stop\n";
});

# 管理进程重载
//$server->on("BeforeReload", function($server) {
//    echo "manager is reload\n";
//});
//
//# 重载结束
//$server->on("AfterReload", function($server) {
//    echo "manager is after reload\n";
//});

#worker进程启动/Task 进程启动，可以再进进程生命周期受用
$server->on("WorkerStart", function($server, $workerId) {
    echo sprintf("worker %s start\n", $workerId);
});

#worker 终止
$server->on("WorkerStop", function($server, $workerId) {
    echo sprintf("worker %s is stop\n", $workerId);
});

#workder 退出
$server->on("WorkerExit", function($server, $workerId) {
    echo sprintf("worker %s is exit\n", $workerId);
});

#结束前
//$server->on("BeforeShutDown", function($server) {
//    echo "the server is before shut down\n";
//});

#结束
$server->on("Close", function($server) {
    echo "the server is close\n";
});



#启动服务器
$server->start();

