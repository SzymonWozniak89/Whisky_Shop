<?php

namespace App\Entity;

use App\Repository\ShipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ShipmentRepository::class)]
class Shipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (name: 'shipment_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'shipment_name', length: 50)]
    private ?string $name = null;

    #[ORM\Column (name: 'shipment_price', type: Types::DECIMAL, precision:8, scale:2)]
    private ?string $price = null;

    #[ORM\OneToMany(mappedBy: 'shipment', targetEntity: Cart::class, cascade:['persist'])]
    private Collection $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setShipment($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getShipment() === $this) {
                $cart->setShipment(null);
            }
        }

        return $this;
    }
}
