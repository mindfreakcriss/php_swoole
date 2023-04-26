<?php

for ($n = 1; $n <= 3; $n++) {
    $process = new \Swoole\Process(function() use ($n) {
        echo "child #" . getmygid() . " start and sleep {$n}s" . PHP_EOL;
        sleep($n);
        echo "child #" . getmygid() . " exit" . PHP_EOL;
    });
    $process->start();
}

for ($n = 3 ; $n--;) {
    $status = \Swoole\Process::wait(true);
    echo "Recycled #{$status['pid']} , code={$status['code']} , signal = {$status['signal']}" . PHP_EOL;
}

echo "Parent #" . getmygid() . ' exit ' . PHP_EOL;