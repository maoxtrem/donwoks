<?php

namespace App\Entity;

use App\Repository\GastoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GastoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Gasto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detalle = null;

    #[ORM\Column(nullable: true)]
    private ?int $precio = null;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechacreate;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechaupdate;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $tipomovimiento = null;

    #[ORM\Column]
    private ?bool $cancelado = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(?string $detalle): static
    {
        $this->detalle = $detalle;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(?int $precio): static
    {
        $this->precio = $precio;

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

    public function getTipoMovimiento(): ?string
    {
        return $this->tipomovimiento;
    }

    public function setTipoMovimiento(string $tipo_mivimiento): static
    {
        $this->tipomovimiento = $tipo_mivimiento;

        return $this;
    }

    public function isCancelado(): ?bool
    {
        return $this->cancelado;
    }

    public function cancelar(): void
    {
       $this->cancelado = true;
    }
    public function setCancelado(bool $cancelado): static
    {
        $this->cancelado = $cancelado;

        return $this;
    }
}
