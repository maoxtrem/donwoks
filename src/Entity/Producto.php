<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $precio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\ManyToOne]
    private ?GrupoProducto $grupo = null;

    #[ORM\Column]
    private ?int $costo = 0;

    #[ORM\Column]
    private ?int $puntos = 0;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
    
    #[ORM\PrePersist]
    public function setColor(?string $color='primary'): static
    {
        $this->color = $color;

        return $this;
    }

    public function getGrupo(): ?GrupoProducto
    {
        return $this->grupo;
    }

    public function setGrupo(GrupoProducto $grupo): static
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getCosto(): ?int
    {
        return $this->costo;
    }

    public function setCosto(?int $costo): static
    {
        $this->costo = $costo;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(?int $puntos): static
    {
        $this->puntos = $puntos;

        return $this;
    }

    
}
