<?php

namespace App\Repository;

use App\Entity\MovimientosInventario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovimientosInventario>
 */
class MovimientosInventarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosInventario::class);
    }

    public function guardar(MovimientosInventario $entity, $flush = true): MovimientosInventario
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();

        return $entity;
    }

    public function get_informe_inventario_all(): array
    {
        $SQL = $this->createQueryBuilder('p')
            ->select(
                'p.nombre',
                'p.medida',
                "SUM(CASE WHEN p.tipomovimiento = 'entrada' THEN p.cantidad ELSE 0 END) AS cantidad_entrada",
                "SUM(CASE WHEN p.tipomovimiento = 'salida' THEN p.cantidad ELSE 0 END) AS cantidad_salida"
            )
            ->groupBy('p.nombre, p.medida');

        return $SQL->getQuery()
            ->getArrayResult();
    }
}
