<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\DetallePedido;
use App\Entity\Pedido;
use App\Repository\ClienteRepository;
use App\Repository\DetallePedidoRepository;
use App\Repository\GrupoProductoRepository;
use App\Repository\PedidoRepository;
use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class PedidoController extends AbstractController
{
    #[Route('/', name: 'app_pedido')]
    public function index(
        Request $request,
        ProductoRepository $productoRepository,
        GrupoProductoRepository $grupoProductoRepository,
        ClienteRepository $clienteRepository
    ): Response {
        $code = $request->query->get('cliente');
        $puntos = 0;
        if ($code != null) {
            $cliente = $clienteRepository->findOneBy(['codigo' => $code]);
            if ($cliente instanceof Cliente) {
                $puntos = $cliente->getPuntos();
            } else {
                $cliente = new Cliente;
                $cliente->setCodigo($code);
                $clienteRepository->guardar($cliente);
            }
        }

        $productos = $productoRepository->findBy([], ['grupo' => 'ASC']);
        $grupos = $grupoProductoRepository->findBy([]);
        return $this->render('pedido/index.html.twig', ['productos' => $productos, 'grupos' => $grupos, 'cliente' => [
            'code' => $code,
            'puntos' => $puntos
        ]]);
    }
    #[Route('/pedidos', name: 'app_pedidos')]
    public function pedidos(PedidoRepository $pedidoRepository): Response
    {
        $productos = $pedidoRepository->findAll();
        return $this->render('pedidos/index.html.twig', ['productos' => $productos]);
    }


    #[Route('/get_pedidos', name: 'app_get_pedidos')]
    public function get_pedidos(PedidoRepository $pedidoRepository): JsonResponse
    {
        $json['rows'] = $pedidoRepository->get_pedidos();
        return new JsonResponse($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/get_pedidos_detalles', name: 'app_get_pedidos_detalles')]
    public function get_pedidos_detalles(
        DetallePedidoRepository $detallePedidoRepository,
        Request $request
    ): JsonResponse {
        $id = $request->getPayload()->getInt('id');
        $json['rows'] = $detallePedidoRepository->get_pedidos_detalles($id);
        return new JsonResponse($json, 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/cancelar_pedido', name: 'app_cancelar_pedido')]
    public function cancelar_pedido(
        PedidoRepository $pedidoRepository,
        ClienteRepository $clienteRepository,
        Request $request
    ): JsonResponse {
        $id = $request->getPayload()->getInt('id');
        $pedido = $pedidoRepository->find($id);
        if ($pedido->getCliente() != null) {
            $cliente = $clienteRepository->findOneBy(['codigo' => $pedido->getCliente()]);
            $cliente->restarPuntos($pedido->getPuntosRecibidos());
            $cliente->sumarPuntos($pedido->getPuntosRedimidos());
            $clienteRepository->guardar($cliente);
        }
        $pedido->cancelar();
        $pedidoRepository->guardar($pedido);
        return new JsonResponse([], 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/procesar_pedido', name: 'app_procesar_pedido')]
    public function app_procesar_pedido(
        PedidoRepository $pedidoRepository,
        Request $request
    ): JsonResponse {
        $id = $request->getPayload()->getInt('id');
        $pedido = $pedidoRepository->find($id);
        $pedido->nextEstado();
        $pedidoRepository->guardar($pedido);
        return new JsonResponse([], 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/pedir', name: 'app_pedir')]
    public function pedir(
        PedidoRepository $pedidoRepository,
        DetallePedidoRepository $detallePedidoRepository,
        ProductoRepository $productoRepository,
        ClienteRepository $clienteRepository,
        Request $request
    ): JsonResponse {
        $code = $request->getPayload()->getString('code');
        $lugar = $request->getPayload()->getString('lugar');
        $metodo_pago = $request->getPayload()->getString('metodo_pago');
        $observacion = $request->getPayload()->getString('observacion');
        $datos = $request->getPayload()->getString('datos');

        $pedido = new Pedido();
        $pedido->setObservacion($observacion);
        $pedido->setLugar($lugar);
        $pedido->setMetodoPago($metodo_pago);
        $pedidoRepository->guardar($pedido);

        $detalles = json_decode($datos);
        $total = 0;
        $utilidad = 0;
        $puntos_redimidos = 0;
        $cliente = $clienteRepository->findOneBy(['codigo' => $code]);
        foreach ($detalles as $detalle) {
            $producto = $productoRepository->find($detalle->id_producto);
            $detalle_pedido = new DetallePedido();
            $detalle_pedido->setPedido($pedido);
            $detalle_pedido->setProducto($producto);
            $detallePedidoRepository->guardar($detalle_pedido, $detalle == end($detalles));
            $total += $producto->getPrecio();
            $utilidad += $producto->getPrecio() - $producto->getCosto();
            if ($producto->getGrupo()->getId() == 8) {
                $puntos_redimidos += $producto->getPuntos();
            }
        }
        if ($cliente instanceof Cliente) {
            $puntos_actuales = $cliente->getPuntos();
            $puntos_recibidos = $utilidad * 0.1;
            $nuevos_puntos =  ($puntos_actuales + $puntos_recibidos) -   $puntos_redimidos;
            $cliente->setPuntos($nuevos_puntos);
            $clienteRepository->guardar($cliente);
            $pedido->setPuntosRecibidos($puntos_recibidos);
            $pedido->setCliente($code);
            $pedido->setPuntosRedimidos($puntos_redimidos);
        }
        $pedido->setPrecio($total);
        $pedido->setUtilidad($utilidad);
        $pedidoRepository->guardar($pedido);

        return new JsonResponse();
    }
}
