<?php 

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;

class RatchetServer implements MessageComponentInterface
{

    protected $server;

    public function __construct()
    {
        $this->server = new \SplObjectStorage;
    }
 
    public function onOpen(ConnectionInterface $conn)
    {
        $this->server->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->server as $client) {
            if ($client !== $from) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->server->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }


 
}