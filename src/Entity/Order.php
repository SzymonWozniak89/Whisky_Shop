<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`Order`')]
class Order
{
    public const STATUS_NEW = 'new';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED = 'canceled';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'order_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'order_status', length: 30)]
    private ?string $status = null;

    #[ORM\Column(name: 'order_amount',  type: Types::DECIMAL, precision:5, scale:2)]
    private ?string $amount = null;

    #[ORM\Column(name: 'order_net_amount', type: Types::DECIMAL, precision:5, scale:2)]
    private ?string $netAmount = null;

    #[ORM\Column(name: 'order_vat_amount', type: Types::DECIMAL, precision:5, scale:2)]
    private ?string $vatAmount = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(referencedColumnName: 'user_id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(referencedColumnName: 'address_id', nullable: false)]
    private ?Address $address = null;

    #[ORM\Column(length: 50, type: Types::DECIMAL, precision:5, scale:2)]
    private ?string $shipmentPrice = null;

    #[ORM\OneToMany(mappedBy: 'orderRef', targetEntity: CartItem::class)]
    private Collection $cartItems;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(referencedColumnName: 'payment_id', nullable: false)]
    private ?Payment $payment = null;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getNetAmount(): ?float
    {
        return $this->netAmount;
    }

    public function setNetAmount(float $netAmount): static
    {
        $this->netAmount = $netAmount;

        return $this;
    }

    public function getVatAmount(): ?float
    {
        return $this->vatAmount;
    }

    public function setVatAmount(float $vatAmount): static
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getShipmentPrice(): ?string
    {
        return $this->shipmentPrice;
    }

    public function setShipmentPrice(string $shipmentPrice): static
    {
        $this->shipmentPrice = $shipmentPrice;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setOrderRef($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getOrderRef() === $this) {
                $cartItem->setOrderRef(null);
            }
        }

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

}
