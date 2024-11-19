<?php

namespace App\Repository;

use App\Entity\DetallePedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<DetallePedido>
 */
class DetallePedidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetallePedido::class);
    }
    public function guardar(DetallePedido $entity, $flush = true): DetallePedido
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
        return $entity;
    }

    public function get_pedidos_detalles(int $id): array
    {
        $SQL = $this->createQueryBuilder('p')
            ->select(
                'p.id id',
                'pr.nombre name',
                'pr.precio price',
            )
            ->leftJoin('p.producto','pr')
            ->andWhere('p.pedido =:pedido')
            ->setParameter('pedido',$id);
        return $SQL->getQuery()
            ->getArrayResult();
    }
}
