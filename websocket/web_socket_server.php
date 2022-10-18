<?php

$ws = new Swoole\WebSocket\Server("0.0.0.0", 9501);

//监听websocket 连接打开事件
$ws->on("Open", function($ws, $request) {
    $ws->push($request->fd, "hello welcome.\n");
});

//监听消息事件
/**
 * $frame 是 Swoole\WebSocket\Frame对象，包含了客户端发送过来的数据帧信息
 *    $frame->fd 客户端socket id 使用 $server->push 需要用到
 *    $frame->data 客户端数据内容，可以是文本也可以是二进制数据，可以通过opcode 值来判断
 *    $frame->opcode Websocket 的Opcode 类型，可以参考Websocket 协议文档 WEBSOCKET_OPCODE_TEXT = 0x1 文本数据 WEBSOCKET_OPCODE_BINARY = 0x2 二进制数据
 *    $frame->finish 表示数据帧是否完整
 */
$ws->on("Message", function($ws, $frame) {
    echo "Message : {$frame->data}\n";

    /**
     * Swoole\Websocket\Server->push(int $fd, string $data , int $opcode = WEBSOCKET_OPCODE_TEXT, bool finish = true) : bool
     */
    /**
     * @Desctpion 打包成websocket的包数据，打包之后可以使用TCP 的send 方法进行发送
     * Swoole\Websocket\Server->pack(string $data, int $opcode = WEBSOCKET_OPCODE_TEXT, bool $finish = true) : bool
     */
    /**
     * @Description 解析Websocket 的数据帧，解析之后生成 $frame 对象
     *
     * Swoole\Websocket\Server->unpack(string $data)
     */

    $ws->push($frame->fd, "Server:{$frame->data}\n");

});

//监听关闭事件
$ws->on("close", function($ws, $fd) {
    echo "client-{$fd} is closed.\n";
});



$ws->start();