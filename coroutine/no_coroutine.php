<?php

$s = microtime(true);
for ($c = 100; $c--;) {
    for ($n = 100; $n--;) {
        usleep(1000);
    }
}

//10k pdo and mysqli read
for ($c = 50; $c--; ) {
    $pdo = new PDO('mysql:host=10.1.5.51:20516;dbname=springgame_admin;charset=utf8', 'root', 'SpringGame123@@#!..');
    $statment = $pdo->prepare("SELECT * FROM t_user");
    for ($n = 100; $n--;) {
        $statment->execute();
        assert(count($statment->fetchAll()) > 0);
    }
}

echo 'use ' . (microtime(true) - $s) . ' s';