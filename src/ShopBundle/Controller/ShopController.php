<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        if(!$orderId || !$cart || $cart->status != 'new') {
            $cart = new Orders();
            $cart->setDate(date('d/m/Y h:i:s a'));
            $cart->setUser();
            $cart->setPayment();
            $cart->addProduct();

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush;

            $session->set('cartId', $cart->getId());
        }
        return $cart;
    }

    /**
     * @Route("/main", name="main_page")
     */
    public function displayAction()
    {
        $repository = $this->getDoctrine()->getRepository('ShopBundle:Category');

        $categories = $repository->findAll();

        dump($categories);

        return $this->render('ShopBundle:shop:main.html.twig', array(
            'categories' => $categories
        ));
    }

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
     * @Route("/addToCart", name="add_product")
     */
    public function addToCartAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
           $this->getCart();
        }
        return new Response("added");
    }
}
