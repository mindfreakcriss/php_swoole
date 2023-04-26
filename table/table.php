<?php



$table = new Swoole\Table(1024);
$table->column("fd", Swoole\Table::TYPE_INT);
$table->column('reactor_id', \Swoole\Table::TYPE_INT);
$table->column('data', \Swoole\Table::TYPE_STRING, 100);
$table->create();

$table->set(1, ['reactor_id' => 1, 'fd' => 1, 'data' => "data"]);

$info = $table->get(1);

var_dump($info);