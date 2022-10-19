<?php

//开启一键协程，后续的所有内容都是协程IO异步

Swoole\Runtime::enableCoroutine();

$s = microtime(true);

/**
 * 对于里面的$flags 解析
 *
 * SWOOLE_HOOK_ALL 打开所有类型，都变成协程 4.5.4 版本之后，包括SWOOLE_HOOK_CURL
 * SWOOLE_HOOK_TCP Redis, PDO, Mysqli 变成协程
 *
 */

echo "Redis\n";

Co\run(function () {
    for ($c = 100; $c--;) {
        go (function () {
            $redis = new Redis();
            $redis->connect("10.1.5.51",6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
            $redis->select(5);
            //获取数据
            $value = $redis->get("CURRENCY_APP1000001");//此处会产生协程调度，cpu 切到下一个进程，不会阻塞进程
        });
    }
});


//计算耗时
echo 'use 协程' . (microtime(true) - $s) . ' s';

echo "\n";

//非协程读取
for ($c = 100; $c--;) {
    $redis = new Redis();
    $redis->connect("10.1.5.51",6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
    $redis->select(5);
    //获取数据
    $value = $redis->get("CURRENCY_APP1000001");//此处会产生协程调度，cpu 切到下一个进程，不会阻塞进程
}

echo 'use 正常' . (microtime(true) - $s) . ' s';

echo "\n";

echo "Mysql\n";

go(function() {
    $pdo = new PDO('mysql:host=127.0.0.1:3306;dbname=springgame_admin;charset=utf8', 'root', '');
    for ( $c = 1000; $c--;) {
        $statement = $pdo->prepare("SELECT * FROM t_user");
        $statement->execute();
    }
});

echo 'use 协程' . (microtime(true) - $s) . ' s';

echo "\n";


$pdo = new PDO('mysql:host=127.0.0.1:3306;dbname=springgame_admin;charset=utf8', 'root', '');
for ( $c = 1000; $c--;) {
    $statement = $pdo->prepare("SELECT * FROM t_user");
    $statement->execute();
}

echo 'use 正常' . (microtime(true) - $s) . ' s';

