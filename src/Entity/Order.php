<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 * @package App\Entity
 * @ORM\Entity()
 */
class Order
{
    // Customer adds goods to cart
    const STATUS_CART = 'cart';
    // Customer filling additional order info
    const STATUS_ORDER = 'order';
    // Customer sent the order and it is being prepared
    const STATUS_PREPARING = 'preparing';
    // The order is ready to be delivered to customer
    const STATUS_READY = 'ready';
    // The order is closed
    const STATUS_DONE = 'done';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    protected $dateTime;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $status;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $deliveryAddress;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $comment;

    /**
     * @var OrderItem[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order")
     */
    protected $orderItems;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return Order
     */
    public function setDateTime(\DateTime $dateTime): Order
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Order
     */
    public function setStatus(string $status): Order
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string|null $deliveryAddress
     * @return Order
     */
    public function setDeliveryAddress(?string $deliveryAddress): Order
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Order
     */
    public function setUser(User $user): Order
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return Order
     */
    public function setComment(?string $comment): Order
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return OrderItem[]|Collection
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItem[]|Collection $orderItems
     * @return Order
     */
    public function setOrderItems($orderItems)
    {
        $this->orderItems = $orderItems;
        return $this;
    }
}