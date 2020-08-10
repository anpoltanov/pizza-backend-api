<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderItem
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(name="order_items")
 */
class OrderItem
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderItems")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $order;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    protected $amount;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return OrderItem
     */
    public function setProduct(Product $product): OrderItem
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return OrderItem
     */
    public function setOrder(Order $order): OrderItem
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return OrderItem
     */
    public function setAmount(float $amount): OrderItem
    {
        $this->amount = $amount;
        return $this;
    }
}