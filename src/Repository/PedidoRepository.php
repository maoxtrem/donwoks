<?php

namespace App\Repository;

use App\Entity\Pedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pedido>
 */
class PedidoRepository extends ServiceEntityRepository
{
   
    
    public function __construct(ManagerRegistry $registry, private \DateTime $hoy = new \DateTime())
    {
        parent::__construct($registry, Pedido::class);
    }
    public function guardar(Pedido $entity, $flush = true): Pedido
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
        return $entity;
    }


    public function get_pedidos(): array
    {
       
        $SQL = $this->createQueryBuilder('p')
            ->select(
                'p.id id',
                'p.precio price',
                'p.observacion observacion',
                'p.fechacreate fecha',
                'p.fechaupdate fecha_final',
                'p.metodoPago metodo_pago',
                'p.lugar lugar',
                'p.cliente cliente',
                'p.puntosRecibidos puntos_recibidos',
                'p.puntosRedimidos puntos_redimidos',
                'p.estado estado',
                "p.utilidad utilidad"
            )
            ->andWhere('p.cancelado = false')
            ->andWhere('p.precio != 0')
            ->andWhere("DATE(p.fechacreate) = DATE(:hoy)")
            ->setParameter('hoy', $this->hoy)
            ->orderBy('p.estado', 'asc');
        return $SQL->getQuery()
            ->getArrayResult();
    }
    public function get_informe_resumen_all(): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                "SUM(p.precio) total_venta",
                "(SELECT SUM(CASE WHEN g.tipomivimiento = 'gasto' THEN g.precio ELSE 0 END) FROM App\Entity\Gasto g WHERE DATE_FORMAT(g.fechacreate, '%Y-%m') = :fecha) total_gasto",
                "(SELECT SUM(CASE WHEN i.tipomivimiento = 'inversion' THEN i.precio ELSE 0 END) FROM App\Entity\Gasto i WHERE DATE_FORMAT(i.fechacreate, '%Y-%m') = :fecha) total_inversion",
               "(SELECT SUM(CASE WHEN c.tipomivimiento = 'cierre_mes' THEN c.precio ELSE 0 END) FROM App\Entity\Gasto c WHERE DATE_FORMAT(c.fechacreate, '%Y-%m') = :fecha) total_cierre",
                "(SELECT SUM(n.cantidad * n.costo) FROM App\Entity\Inventario n) activos_circulantes",
                "SUM(p.utilidad)  total_utilidad",
                "SUM(p.precio) caja",

            )
            ->andWhere("p.cancelado = false")
            ->andWhere("p.precio != 0")
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->getQuery()
            ->getArrayResult();
    }

    public function get_informe_pedidos_all(): array
    {
 
        return $this->createQueryBuilder('p')
            ->select(
                "DATE(p.fechacreate) fecha",
                "p.metodoPago metodo_pago",
                "COUNT(p.id)  total_pedidos",
                "SUM(p.precio)  total_precio",
                "SUM(p.utilidad)  total_utilidad",

            )
            ->where('p.cancelado = false')
            ->andWhere('p.precio != 0')
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->addGroupBy('fecha')
            ->addGroupBy('p.metodoPago')
            ->orderBy('fecha', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getFechas()
    {
        return $this->createQueryBuilder('p')
            ->select("DATE(p.fechacreate) fecha")
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->addGroupBy('fecha')
            ->orderBy('fecha', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
    public function getVentas()
    {
        return $this->createQueryBuilder('p')
            ->select(
                "SUM(p.precio) ventas",
                "DATE(p.fechacreate) fecha",
                "SUM(p.utilidad)  utilidad",
                "SUM(p.precio) - SUM(p.utilidad) costo"
            )
            ->where('p.cancelado = false')
            ->andWhere("DATE_FORMAT(p.fechacreate, '%Y-%m') = :fecha")
            ->setParameter('fecha', $this->hoy->format('Y-m'))
            ->addGroupBy('fecha')
            ->orderBy('fecha', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function fixFecha(): ?Pedido
    {
      
        $SQL = $this->createQueryBuilder('p')
            ->where('p.cancelado = false')
            ->andWhere("DATE(p.fechacreate) = DATE(:hoy)")
            ->setParameter('hoy', $this->hoy)

            ->setMaxResults(1);
        return $SQL->getQuery()
            ->getOneOrNullResult();
    }
}
