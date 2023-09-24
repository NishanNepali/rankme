<?php

require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Create a WebSocket application
class WebSocketServer implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $conn)
    {
        // Handle new connection
        echo "New connection\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Handle connection close
        echo "Connection closed\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Handle received message
        echo "Received message: $msg\n";
        // You can send a message back to the client using:
        // $from->send('Hello, client!');
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Handle error
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Create a WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketServer()
        )
    ),
    8080
);

// Start the server
$server->run();
?>