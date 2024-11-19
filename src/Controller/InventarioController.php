<?php

namespace App\Controller;

use App\Entity\MovimientosInventario;
use App\Repository\InventarioRepository;
use App\Repository\MovimientosInventarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InventarioController extends AbstractController
{
    #[Route('/inventario', name: 'app_inventario')]
    public function index(): Response
    {
        return $this->render('inventario/index.html.twig');
    }

    #[Route('/get_inventario', name: 'app_get_inventario')]
    public function get_inventario(
        InventarioRepository $inventarioRepository
    ): JsonResponse {
        $json['rows'] = $inventarioRepository->get_inventario();
        return new JsonResponse($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/enviar_inventario', name: 'app_enviar_inventario')]
    public function  enviar_inventario(
        InventarioRepository $inventarioRepository,
        MovimientosInventarioRepository $movimientosInventarioRepository,
        Request $request
    ): JsonResponse {
        $datos = $request->getPayload()->getString('datos');
        $productos = json_decode($datos);

        foreach ($productos as $producto) {
            $item = $inventarioRepository->find($producto->id);

            if ($producto->new_cantidad || $producto->add_cantidad) {
                $movimientosInventario = new MovimientosInventario();
                $movimientosInventario->setNombre($item->getNombre());
                $movimientosInventario->setMedida($item->getMedida());
                if ($producto->new_cantidad) {
                    $movimientosInventario->setTipomovimiento('salida');
                    $movimientosInventario->setCantidad($item->getCantidad() - $producto->new_cantidad);
                    $item->setCantidad($producto->new_cantidad);
                }
                if ($producto->add_cantidad) {
                    $item->setCantidad($producto->add_cantidad + $item->getCantidad());
                    $movimientosInventario->setTipomovimiento('entrada');
                    $movimientosInventario->setCantidad($producto->add_cantidad);
                }
                $movimientosInventarioRepository->guardar($movimientosInventario);
            }

            $inventarioRepository->guardar($item, $producto == end($productos));
        }
        return new JsonResponse([], 200, ['Content-Type' => 'application/json']);
    }
}
