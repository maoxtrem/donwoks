<?php

namespace App\Controller;

use App\Entity\Gasto;
use App\Entity\Pedido;
use App\Repository\GastoRepository;
use App\Repository\MovimientosInventarioRepository;
use App\Repository\PedidoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InformesController extends AbstractController
{
    #[Route('/informes', name: 'app_informes')]
    public function index(): Response
    {
        return $this->render('informes/index.html.twig');
    }

    #[Route('/get_informe_pedidos_all', name: 'app_get_informe_pedidos_all')]
    public function get_informe_pedidos_all(
        PedidoRepository $pedidoRepository
    ): JsonResponse
    {
        $josn['rows']=$pedidoRepository->get_informe_pedidos_all();
        return new JsonResponse( $josn);
    }

    #[Route('/get_informe_gastos_inversion_all', name: 'app_get_informe_gastos_inversion_all')]
    public function get_informe_gastos_inversion_all(
        GastoRepository $gastoRepository
    ): JsonResponse
    {
        $josn['rows']=$gastoRepository->get_informe_gastos_inversion_all();
        return new JsonResponse( $josn);
    }


    
    #[Route('/get_informe_inventario_all', name: 'app_get_informe_inventario_all')]
    public function get_informe_inventario_all(
        MovimientosInventarioRepository $movimientosInventarioRepository
    ): JsonResponse
    {
        $josn['rows']=$movimientosInventarioRepository->get_informe_inventario_all();
        return new JsonResponse( $josn);
    }

    #[Route('/get_informe_resumen_all', name: 'app_get_informe_resumen_all')]
    public function get_informe_resumen_all(
        PedidoRepository $pedidoRepository
    ): JsonResponse
    {
        $josn['rows']=$pedidoRepository->get_informe_resumen_all();
        return new JsonResponse( $josn);
    }
    
    

    #[Route('/get_data_grafic', name: 'app_get_data_grafic')]
    public function get_data_grafic(
        PedidoRepository $pedidoRepository,
        GastoRepository $gastoRepository
    ): JsonResponse
    {
      $venta  = $pedidoRepository->fixFecha();
      $gastos  = $gastoRepository->fixFechaGasto();
      $inversion  = $gastoRepository->fixFechaInversion();

      if(!$venta instanceof Pedido){
        $venta = new Pedido;
        $venta->setLugar('llevar'); 
        $venta->setObservacion('fix'); 
        $venta->setPrecio(0); 
        $venta->setUtilidad(0);
        $venta->setMetodoPago('efectivo');
        $pedidoRepository->guardar($venta);
      }

      if(!$gastos instanceof Gasto){
        $gastos = new Gasto;
        $gastos->setPrecio(0); 
        $gastos->setTipoMovimiento('gasto');
        $gastos->setDetalle('fix'); 
        $gastoRepository->guardar($gastos);
      }

      if(!$inversion instanceof Gasto){
        $inversion = new Gasto;
        $inversion->setPrecio(0); 
        $inversion->setDetalle('fix');
        $inversion->setTipoMovimiento('inversion'); 
        $gastoRepository->guardar($inversion);
      }

        $json['fecha']=array_column($pedidoRepository->getFechas(), 'fecha');
        $json['ventas']=array_column($pedidoRepository->getVentas(), 'ventas');
        $json['utilidad']=array_column($pedidoRepository->getVentas(), 'utilidad');
        $json['costo']=array_column($pedidoRepository->getVentas(), 'costo');
        $json['gastos']=array_column($gastoRepository->get_gastos_all(), 'gastos');
        $json['inversion']=array_column($gastoRepository->get_inversion_all(), 'inversion');
        return new JsonResponse( $json);
    }
   
}
