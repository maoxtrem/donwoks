<?php

namespace App\Controller;

use App\WebSocket\RatchetClient;
use App\WebSocket\RatchetServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 

class WebsocketController extends AbstractController
{
    

    #[Route('/websocket', name: 'app_websocket')]
    public function index(): Response
    {
        return $this->render('websocket/index.html.twig');
    }

    #[Route('/websocket_test', name: 'app_websocket_test')]
    public function test(RatchetClient $ratchetClient): Response
    {
        $ratchetClient->send('nuevo pedido');
        return new Response();
    }
}
