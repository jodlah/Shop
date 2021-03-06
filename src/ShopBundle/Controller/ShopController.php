<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Orders;
use ShopBundle\Entity\OrdersProducts;
use ShopBundle\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ShopController extends Controller
{

    private function getCart($session)
    {
        $orderId = $session->get('cartId');

        $repository = $this->getDoctrine()->getRepository('ShopBundle:Orders');

        if($orderId) {
            $cart = $repository->find($orderId);

        }
        if(!$orderId || !$cart ) {
            $cart = new Orders();

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

            $session->set('cartId', $cart->getId());
        }
        return $cart;
    }

    //main page controller
    /**
     * @Route("/main", name="main_page")
     */
    public function displayAction()
    {
        $repository = $this->getDoctrine()->getRepository('ShopBundle:Category');

        $categories = $repository->findAll();

        return $this->render('ShopBundle:shop:main.html.twig', array(
            'categories' => $categories
        ));
    }

    //search engine
    /**
     * @Route("/showProducts", name="show_products")
     * @Method("POST")
     */
    public function searchAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('ShopBundle:Products');
        $repositoryCategory = $this->getDoctrine()->getRepository('ShopBundle:Category');

        $name = $request->request->get('name');
        $category = $request->request->get('categories');

        dump($category);

        if($category AND !empty($name)) {
            $products = $repository->findProductsByNameAndCategory($name, $repositoryCategory->find($category));
        } elseif (!empty($name)) {
            $products = $repository->findProductsByName($name);
        }

        dump($products);

        return $this->render('ShopBundle:products:showProducts.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * @Route("/addToCart/{id}", name="add_product")
     */
    public function addToCartAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {

            $session = $request->getSession();

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $repository = $this->getDoctrine()->getRepository('ShopBundle:Products');
            $productToAdd = $repository->find($id);

            $cart = $this->getCart($session);

            $cart->setDate(new \DateTime());
            $cart->setUser($this->getUser());

            $found = false;
            //pobieram wszystkie elementy które są w koszyku
            foreach ($cart->getOrdersProducts() as $ordersProduct) {
                if($ordersProduct->getProducts()->getId() == $id){
                    $ordersProduct->setCount($ordersProduct->getCount() + 1);
                    $found = true;
                    break;
                }
            }
            if(!$found) {
                $ordersProduct = new OrdersProducts();
                $ordersProduct->setOrder($cart);
                $ordersProduct->setProducts($productToAdd);
                $ordersProduct->setCount(1);

                $em->persist($ordersProduct);
            }
            $em->flush();
        }
        return $this->redirectToRoute('products_show', ['id' => $id]);
    }

    /**
     * @Route("/showCart"), name="show_cart")
     */
    public function showCartAction(Request $request)
    {
        $cart = $this->getCart($request->getSession());

        if (!$cart) {
            throw new Exception('There is no cart');
        } else {
            $cartItem = $cart->getOrdersProducts();
        }

        $sum = 0;

        foreach ($cart->getOrdersProducts() as $ordersProduct) {
            $price = $ordersProduct->getProducts()->getPrice();
            $count = $ordersProduct->getCount();
            $amount = ($price * $count);
            $sum +=$amount;
        }

        return $this->render('ShopBundle:shop:cart.html.twig', array(
            'cartItem' => $cartItem,
            'sum' => $sum
        ));
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homePageAction()
    {
        return $this->redirectToRoute('main_page');
    }
}
