<?php

namespace App\Repository;

use App\Entity\Gasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gasto>
 */
class GastoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private \DateTime $hoy = new \DateTime())
    {
        parent::__construct($registry, Gasto::class);
    }

    public function guardar(Gasto $entity, $flush = true): Gasto
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();

        return $entity;
    }


    public function get_gastos(?string $movimiento = null): array
    {

        $SQL = $this->createQueryBuilder('p')
            ->select(
                'p.id id',
                'p.fechacreate fecha',
                'p.detalle detalle',
                'p.precio precio',
                'p.tipomovimiento tipo_movimiento'
            )
            ->andWhere("DATE(p.fechacreate) = DATE(:hoy)")
            ->andWhere('p.precio != 0')
            ->setParameter('hoy', $this->hoy);
        $movimiento && $SQL->andWhere('p.tipomovimiento = :movimiento')
            ->setParameter('movimiento', $movimiento);
        return $SQL->getQuery()
            ->getArrayResult();
    }
    public function get_deudas(?string $movimiento = null): array
    {

        $SQL = $this->createQueryBuilder('p')
            ->select(
                'p.id id',
                'p.fechacreate fecha',
                'p.detalle detalle',
                'p.precio precio',
                'p.tipomovimiento tipo_movimiento',
                '0 abono'

            )
           #->andWhere('p.detalle = :nequi')->setParameter('nequi', 'nequi')
            ->andWhere('p.precio != 0')
            ->andWhere('p.cancelado = 0')
            ;
      
        $movimiento && $SQL->andWhere('p.tipomovimiento = :movimiento')      
            ->setParameter('movimiento', $movimiento);
          
        return $SQL->getQuery()
            ->getArrayResult();
    }






    public function get_informe_gastos_inversion_all(): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                "DATE(p.fechacreate) fecha",
                "SUM(CASE WHEN p.tipomovimiento = 'gasto' THEN p.precio ELSE 0 END) total_gasto",
                "SUM(CASE WHEN p.tipomovimiento = 'inversion' THEN p.precio ELSE 0 END) total_inversion"
            )
            ->andWhere("p.tipomovimiento IN ('gasto', 'inversion')")
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->addGroupBy('fecha')
            ->orderBy('fecha', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function get_gastos_all(): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                "DATE(p.fechacreate) fecha",
                "SUM(p.precio)  gastos"
            )
            ->andWhere("p.tipomovimiento = 'gasto'")
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->addGroupBy('fecha')
            ->orderBy('fecha', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function get_inversion_all(): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                "DATE(p.fechacreate) fecha",
                "SUM(p.precio)  inversion"
            )
            ->andWhere("p.tipomovimiento = 'inversion'")
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->addGroupBy('fecha')
            ->orderBy('fecha', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
    public function fixFechaGasto(): ?Gasto
    {
        return $this->fixFecha('gasto');
    }

    public function fixFechaInversion(): ?Gasto
    {
        return $this->fixFecha('inversion');
    }

    public function fixFecha(string $movimiento): ?Gasto
    {
        $SQL = $this->createQueryBuilder('p')
            ->andWhere("p.tipomovimiento = :movimiento")
            ->setParameter('movimiento', $movimiento)
            ->andWhere("DATE(p.fechacreate) = DATE(:hoy)")
            ->setParameter('hoy', $this->hoy)
            ->setMaxResults(1);
        return $SQL->getQuery()
            ->getOneOrNullResult();
    }
}
