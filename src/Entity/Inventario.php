<?php

namespace App\Entity;

use App\Repository\InventarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventarioRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Inventario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?int $cantidad = null;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechacreate;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechaupdate;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $medida = null;

    #[ORM\Column]
    private int $minimo = 0;

    #[ORM\Column]
    private ?int $costo = 0;

    #[ORM\Column]
    private ?int $estado = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }


    public function getFechacreate(): ?\DateTime
    {
        return $this->fechacreate;
    }

    #[ORM\PrePersist]
    public function setFechacreate(): void
    {
        $this->fechacreate = new \DateTime();
    }

    public function getFechaupdate(): ?\DateTime
    {
        return $this->fechaupdate;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setFechaupdate(): void
    {
        $this->fechaupdate = new \DateTime();
    }

    public function getMedida(): ?string
    {
        return $this->medida;
    }

    public function setMedida(?string $medida): static
    {
        $this->medida = $medida;

        return $this;
    }

    public function getMinimo(): ?int
    {
        return $this->minimo;
    }

    public function setMinimo(?int $minimo): static
    {
        $this->minimo = $minimo;

        return $this;
    }

    public function getCosto(): ?int
    {
        return $this->costo;
    }

    public function setCosto(int $costo): static
    {
        $this->costo = $costo;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }


    public function setEstado(int $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
}
