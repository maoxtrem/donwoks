<?php

namespace App\Controller;

use App\Entity\Gasto;
use App\Repository\GastoRepository;
use LDAP\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GastoController extends AbstractController
{
    #[Route('/gasto', name: 'app_gasto')]
    public function index(): Response
    {
        return $this->render('gasto/index.html.twig');
    }

    #[Route('/gasto_enviar', name: 'app_gasto_enviar')]
    public function gasto_enviar(
        Request $request,
        GastoRepository $gastoRepository
    ): JsonResponse {
        $valor = $request->getPayload()->getInt('valor');
        $concepto = $request->getPayload()->getString('concepto');
        $tipo_movimiento = $request->getPayload()->getString('tipo_movimiento');

        $gasto = new Gasto();
        $gasto->setDetalle($concepto);
        $gasto->setPrecio($valor);
        $gasto->setTipoMivimiento($tipo_movimiento);
        $gastoRepository->guardar($gasto);
        $json['rows'] = [];
        return new JsonResponse($json, 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/get_gasto', name: 'app_get_gasto')]
    public function get_gasto(
        GastoRepository $gastoRepository,
        Request $request
    ): JsonResponse {
        $movimirnto = $request->query->get('movimiento');

        if (in_array($movimirnto, ['deuda', 'credito', 'prestamo'])) {
            $json['rows'] = $gastoRepository->get_gastos($movimirnto);
            return new JsonResponse($json, 200, ['Content-Type' => 'application/json']);
        }
        $json['rows'] = $gastoRepository->get_gastos($movimirnto);
        return new JsonResponse($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/cancelar_gasto', name: 'app_cancelar_gasto')]
    public function cancelar_gasto(
        GastoRepository $gastoRepository,
        Request $request
    ): JsonResponse {
        $id = $request->request->get('id');
        $gasto = $gastoRepository->findOneBy(['id' => $id]);
        if ($gasto instanceof Gasto) {
            $gasto->cancelar();
           // $gastoRepository->guardar($gasto);
        }
        return new JsonResponse(['movimiento'=>$gasto->getTipoMivimiento()], 200, ['Content-Type' => 'application/json']);
    }
}
