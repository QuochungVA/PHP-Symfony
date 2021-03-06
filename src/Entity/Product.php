<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ProductDetail::class, inversedBy: 'products')]
    private $ProductDetailId;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'float')]
    private $Price;

    #[ORM\Column(type: 'text')]
    private $Image;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductDetailId(): ?ProductDetail
    {
        return $this->ProductDetailId;
    }

    public function setProductDetailId(?ProductDetail $ProductDetailId): self
    {
        $this->ProductDetailId = $ProductDetailId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }
}
