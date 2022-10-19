<?php

### 协程遇到IO会自动切换，所以需要注意输出的地方，因为协程是同步代码异步执行，这一点要注意

\Co\run(function() {
    go (function() {
        go (function() {
            co::sleep(1);
            echo "this is inner coroutine\n";
        });
        echo "this is outer coroutine\n";
        co::sleep(1);
    });
    echo "this is first coroutine\n";
});


go (function () {

    $wait = new Swoole\Coroutine\WaitGroup();

    $channel = new Swoole\Coroutine\Channel(12);

    for ($i = 0; $i < 12; $i++) {
        $wait->add();
        go(function () use ($wait, $i, $channel) {
            $channel->push("这是第{$i}数据.\n");
            $wait->done();
        });
    }

    $wait->wait();

    while (true) {
        if ($channel->isEmpty()) {
            break;
        }
        echo $channel->pop();
    }
});
