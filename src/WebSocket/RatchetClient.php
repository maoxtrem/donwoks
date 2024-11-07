<?php

namespace App\WebSocket;


class RatchetClient
{
    public  function send($msg = 'no hay mensaje')
    {
        \Ratchet\Client\connect('ws://localhost:8000')->then(function ($conn) use ($msg) {
            $conn->send($msg);
            $conn->close();
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}
