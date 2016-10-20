<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductsRepository")
 */
class Products
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Products
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Products
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category
     *
     * @param \ShopBundle\Entity\Category $category
     * @return Products
     */
    public function setCategory(\ShopBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \ShopBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add ordersProducts
     *
     * @param \ShopBundle\Entity\OrdersProducts $ordersProducts
     * @return Products
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
