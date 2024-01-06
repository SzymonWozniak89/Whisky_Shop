<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    final public const STATUS_CREATED = 'created';

    final public const STATUS_CONFIRMED = 'confirmed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'cart_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'cart_status', length: 50)]
    private ?string $status = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist'])]
    #[ORM\JoinColumn(referencedColumnName: 'user_id', nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $cartItems;

    #[ORM\ManyToOne(inversedBy: 'carts', targetEntity: Shipment::class, cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(referencedColumnName: 'shipment_id', nullable: true)]
    private ?shipment $shipment = null;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getItems(): ?Collection
    {
        return $this->cartItems;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function addItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function add(Product $product)
    {
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(1);
        $this->addItem($cartItem);
    }

    public function getShipment(): ?shipment
    {
        return $this->shipment;
    }

    public function setShipment(?shipment $shipment): static
    {
        $this->shipment = $shipment;

        return $this;
    }

}
