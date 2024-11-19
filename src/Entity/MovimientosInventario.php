<?php

namespace App\Entity;

use App\Repository\MovimientosInventarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovimientosInventarioRepository::class)]
#[ORM\HasLifecycleCallbacks]
class MovimientosInventario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?int $cantidad = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tipomovimiento = null;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechacreate;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechaupdate;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $medida = null;

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

    public function getTipomovimiento(): ?string
    {
        return $this->tipomovimiento;
    }

    public function setTipomovimiento(?string $tipomovimiento): static
    {
        $this->tipomovimiento = $tipomovimiento;

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

}
