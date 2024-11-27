<?php

namespace App\Repository;

use App\Entity\Inventario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventario>
 */
class InventarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private \DateTime $hoy = new \DateTime())
    {
        parent::__construct($registry, Inventario::class);
    }

    public function guardar(Inventario $entity, $flush = true): Inventario
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();

        return $entity;
    }
    
    public function get_inventario(): array
    {

        $SQL = $this->createQueryBuilder('p')
            ->select(
                'p.id id',
                'p.fechaupdate fecha',
                'p.nombre nombre',
                'p.cantidad cantidad',
                'p.medida medida',
                'p.cantidad * p.costo costo',
                '(p.cantidad / p.minimo)*100 minimo',
                '0 AS new_cantidad',
                '0 AS add_cantidad'
            )
            ->andWhere("DATE(p.fechaupdate) != DATE(:hoy)")
            ->setParameter('hoy', $this->hoy)
            //->addOrderBy('fecha','ASC')
            ->addOrderBy('minimo','ASC');

        return $SQL->getQuery()
            ->getArrayResult();
    }
}
