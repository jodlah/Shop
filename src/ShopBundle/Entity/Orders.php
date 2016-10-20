<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\OrdersRepository")
 */
class Orders
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(nullable=true)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Payment", mappedBy="orders")
     */
    private $payment;

    /**
     * @ORM\OneToMany(targetEntity="OrdersProducts", mappedBy="order")
     */
    private $ordersProducts;

    public function __construct()
    {
        $this->ordersProducts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Orders
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Orders
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set payment
     *
     * @param \ShopBundle\Entity\Payment $payment
     * @return Orders
     */
    public function setPayment(\ShopBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \ShopBundle\Entity\Payment 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Add ordersProducts
     *
     * @param \ShopBundle\Entity\OrdersProducts $ordersProducts
     * @return Orders
     */
    public function addOrdersProduct(\ShopBundle\Entity\OrdersProducts $ordersProducts)
    {
        $this->ordersProducts[] = $ordersProducts;

        return $this;
    }

    /**
     * Remove ordersProducts
     *
     * @param \ShopBundle\Entity\OrdersProducts $ordersProducts
     */
    public function removeOrdersProduct(\ShopBundle\Entity\OrdersProducts $ordersProducts)
    {
        $this->ordersProducts->removeElement($ordersProducts);
    }

    /**
     * Get ordersProducts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrdersProducts()
    {
        return $this->ordersProducts;
    }
}
