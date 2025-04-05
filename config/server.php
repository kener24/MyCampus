<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';

class MySocket implements MessageComponentInterface {protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nueva conexión ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        // Si el mensaje es un evento de like, retransmitir a todos
        if (isset($data['type']) && $data['type'] === 'like') {
            foreach ($this->clients as $client) {
                // No solo al que envió, sino a todos
                $client->send(json_encode([
                    'type' => 'like_update',
                    'post_id' => $data['post_id']
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Conexión cerrada ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MySocket()
        )
    ),
    8080
);

echo "Servidor WebSocket escuchando en puerto 8080...\n";
$server->run();
