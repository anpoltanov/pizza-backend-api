<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $imageUrl;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    protected $priceEUR;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    protected $priceUSD;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Product
     */
    public function setDescription(?string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     * @return Product
     */
    public function setImageUrl(?string $imageUrl): Product
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPriceEUR(): ?float
    {
        return $this->priceEUR;
    }

    /**
     * @param float|null $priceEUR
     * @return Product
     */
    public function setPriceEUR(?float $priceEUR): Product
    {
        $this->priceEUR = $priceEUR;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPriceUSD(): ?float
    {
        return $this->priceUSD;
    }

    /**
     * @param float|null $priceUSD
     * @return Product
     */
    public function setPriceUSD(?float $priceUSD): Product
    {
        $this->priceUSD = $priceUSD;
        return $this;
    }
}