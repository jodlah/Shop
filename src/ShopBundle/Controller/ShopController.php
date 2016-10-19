<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ShopController extends Controller
{
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
     * @Route("/showSearchedProducts", name="show_searched_products")
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

        return $this->render('ShopBundle:products:showSearchedProducts.html.twig', array(
            'products' => $products
        ));
    }


}
