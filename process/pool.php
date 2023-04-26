<?php

use Swoole\Process;

$pool = new Process\Pool(5);

$pool->set(['enable_coroutine' => true]);

$pool->on("WorkerStart", function(Process\Pool $pool, $workerId) {
    /**
     * Worker 进程
     */
    static $running = true;
    Process::signal(SIGTERM, function() use (&$running) {
        $running = false;
        echo "TERM\n";
    });
    echo("[Worker #{$workerId}] WorkerStart, pid: " . posix_getpid() . "\n");
    while($running) {
        \Swoole\Coroutine::sleep(1);
        echo "sleep 1\n";
    }
});

$pool->on("WorkerStop", function(\Swoole\Process\Pool $pool, $workderId) {
    echo("[Worker #{$workderId}] WorkerStop\n");
});

$pool->start();