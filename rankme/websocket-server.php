<?php

require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Define your WebSocket server class
class MyWebSocketServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection when it is opened
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection when it is closed
        $this->clients->detach($conn);
    }

    public function onMessage(ConnectionInterface $from, $message) {
        // Handle WebSocket messages
        // ...

        // Example: Broadcast received message to all connected clients
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Handle errors
    }
}

// Create a WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocketServer()
        )
    ),
    8080 // Specify the port on which the WebSocket server will run
);

// Enable CORS support
$server->loop->addPeriodicTimer(1, function () use ($server) {
    foreach ($server->httpHosts as $httpHost) {
        $httpHost->responseHeaderManager->addHeaders([
            'Access-Control-Allow-Origin' => 'http://localhost',
            'Access-Control-Allow-Headers' => 'Content-Type',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
        ]);
    }
});

// Handle OPTIONS requests for the polling transport
$server->loop->addPeriodicTimer(1, function () use ($server) {
    foreach ($server->httpHosts as $httpHost) {
        $httpHost->on('request', function ($request, $response) {
            if ($request->getMethod() === 'OPTIONS' && $request->getUri()->getPath() === '/socket.io/') {
                $response->setStatus(200);
                $response->setHeader('Access-Control-Allow-Origin', 'http://localhost');
                $response->setHeader('Access-Control-Allow-Headers', 'Content-Type');
                $response->end();
            }
        });
    }
});

echo "WebSocket server started." . PHP_EOL;

// Run the WebSocket server
$server->run();


?>