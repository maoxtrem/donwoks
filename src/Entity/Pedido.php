<?php

namespace App\Entity;

use App\Repository\PedidoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Pedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codigo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $precio = null;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechacreate;

    #[ORM\Column(nullable: true)]
    private \DateTime $fechaupdate;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $observacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lugar = null;

    #[ORM\Column]
    private ?int $estado = 0;

    #[ORM\Column]
    private ?bool $cancelado = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metodoPago = null;

    #[ORM\Column]
    private ?int $utilidad = 0;

    #[ORM\Column]
    private ?int $puntosRecibidos = 0;

    #[ORM\Column]
    private ?int $puntosRedimidos = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cliente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    #[ORM\PrePersist]
    public function setCodigo(): void
    {
        $this->codigo = uniqid();
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): self
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

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): static
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(?string $lugar): static
    {
        $this->lugar = $lugar;

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
    
    public function nextEstado(): void
    {
        $this->estado += 1;
    }

    public function finalEstado(): void
    {
        $this->estado = 3;
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

    public function getMetodoPago(): ?string
    {
        return $this->metodoPago;
    }

    public function setMetodoPago(?string $metodoPago): static
    {
        $this->metodoPago = $metodoPago;

        return $this;
    }

    public function getUtilidad(): ?int
    {
        return $this->utilidad;
    }

    public function setUtilidad(?int $utilidad): static
    {
        $this->utilidad = $utilidad;

        return $this;
    }



    public function getPuntosRecibidos(): ?int
    {
        return $this->puntosRecibidos;
    }

    public function setPuntosRecibidos(int $puntosRecibidos): static
    {
        $this->puntosRecibidos = $puntosRecibidos;

        return $this;
    }

    public function getPuntosRedimidos(): ?int
    {
        return $this->puntosRedimidos;
    }

    public function setPuntosRedimidos(int $puntosRedimidos): static
    {
        $this->puntosRedimidos = $puntosRedimidos;

        return $this;
    }

    public function getCliente(): ?string
    {
        return $this->cliente;
    }

    public function setCliente(?string $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }
}
