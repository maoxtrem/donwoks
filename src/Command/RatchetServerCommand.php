<?php

namespace App\Command;

use App\WebSocket\RatchetServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

class RatchetServerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('websocket:server')
            ->setDescription('Iniciar servidor Ratchet');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
  
        $ws = new WsServer(new RatchetServer);

        // Make sure you're running this as root
        $server = IoServer::factory(new HttpServer($ws), 8000);
        $server->run();
    }
}