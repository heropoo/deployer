<?php
/**
 * User: heropoo
 * Datetime: 2024/9/12 11:36
 */
use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request as HttpRequest;

require_once __DIR__.'/../vendor/autoload.php';


// #### http worker ####
$http_worker = new Worker('http://0.0.0.0:2345');

// 4 processes
$http_worker->count = 4;

// Emitted when data received
$http_worker->onMessage = function (TcpConnection $connection, HttpRequest $request) {
    //$request->get();
    //$request->post();
    //$request->header();
    //$request->cookie();
    //$request->session();
    //$request->uri();
    //$request->path();
    //$request->method();

    var_dump(get_class($request));

    $indexHtml = file_get_contents(__DIR__.'/1.html');

    // Send data to client
//    $connection->send("Hello World");
    $connection->send($indexHtml);

};

// -----------------------------------------------------------------

// Create a Websocket server
$ws_worker = new Worker('websocket://0.0.0.0:2346');

// Emitted when new connection come
$ws_worker->onConnect = function ($connection) {
    echo "New connection\n";
};

// Emitted when data received
$ws_worker->onMessage = function ($connection, $data) {
    // Send hello $data
    $connection->send('Hello ' . $data);
};

// Emitted when connection closed
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n";
};

// -----------------------------------------------------------------

// Run all workers
Worker::runAll();