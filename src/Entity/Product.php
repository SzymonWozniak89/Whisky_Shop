<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'product_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'product_name', length: 100)]
    private ?string $name = null;

    #[ORM\Column(name: 'product_has_active_sale')]
    private ?bool $hasActiveSale = null;

    #[ORM\Column(name: 'product_description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'product_base_price')]
    private ?float $basePrice = null;

    #[ORM\Column(name: 'product_price')]
    private ?float $price = null;

    #[ORM\Column(name: 'product_img', length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(name: 'product_volume')]
    private ?int $volume = null;

    #[ORM\Column(name: 'product_ABV')]
    private ?int $abv = null;

    #[ORM\Column(name: 'product_stock')]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'product', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'brand_id', nullable: false)]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(inversedBy: 'product', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'category_id', nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'product')]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isHasActiveSale(): ?bool
    {
        return $this->hasActiveSale;
    }

    public function setHasActiveSale(bool $hasActiveSale): static
    {
        $this->hasActiveSale = $hasActiveSale;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getABV(): ?int
    {
        return $this->abv;
    }

    public function setABV(int $abv): static
    {
        $this->abv = $abv;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

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
            $cartItem->addProduct($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            $cartItem->removeProductId($this);
        }

        return $this;
    }
}
