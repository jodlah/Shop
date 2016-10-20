<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ordersproducts
 *
 * @ORM\Table(name="ordersproducts")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ordersProductsRepository")
 */
class OrdersProducts
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
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="Orders", inversedBy="ordersProducts")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Products", inversedBy="ordersProducts")
     * @ORM\JoinColumn(name="products_id", referencedColumnName="id")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Set count
     *
     * @param integer $count
     * @return OrdersProducts
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set order
     *
     * @param \ShopBundle\Entity\Orders $order
     * @return OrdersProducts
     */
    public function setOrder(\ShopBundle\Entity\Orders $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \ShopBundle\Entity\Orders 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set products
     *
     * @param \ShopBundle\Entity\Products $products
     * @return OrdersProducts
     */
    public function setProducts(\ShopBundle\Entity\Products $products = null)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get products
     *
     * @return \ShopBundle\Entity\Products 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
