<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'cartItem_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'cartItem_quantity')]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems', cascade:["persist"])]
    #[ORM\JoinColumn(referencedColumnName: 'cart_id', nullable: false)]
    private ?Cart $cart = null;

    #[ORM\OneToOne(targetEntity: Product::class, inversedBy: 'cartItem')]
    #[ORM\JoinColumn(referencedColumnName: 'product_id', name: 'product_id', nullable: false)]
    private ?Product $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getTotalPrice()
    {
        return $this->getProduct()->getPrice()*$this->getQuantity(); 
    }
}
