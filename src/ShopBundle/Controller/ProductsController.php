<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Products;
use ShopBundle\Form\ProductsType;

/**
 * Products controller.
 *
 * @Route("/products")
 */
class ProductsController extends Controller
{
    /**
     * Lists all Products entities.
     *
     * @Route("/", name="products_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('ShopBundle:products:index.html.twig');
    }

    /**
     * Lists all Products entities.
     *
     * @Route("/all", name="products_all")
     * @Method("GET")
     */
    public function showAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('ShopBundle:Products')->findAll();



        return $this->render('ShopBundle:products:showAllProducts.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new Products entity.
     *
     * @Route("/new", name="products_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Products();
        $form = $this->createForm('ShopBundle\Form\ProductsType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products_show', array('id' => $product->getId()));
        }

        return $this->render('ShopBundle:products:new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Products entity.
     *
     * @Route("/{id}", name="products_show")
     * @Method("GET")
     */
    public function showAction(Products $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('ShopBundle:products:show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Products entity.
     *
     * @Route("/{id}/edit", name="products_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Products $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('ShopBundle\Form\ProductsType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products_edit', array('id' => $product->getId()));
        }

        return $this->render('ShopBundle:products:edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Products entity.
     *
     * @Route("/{id}", name="products_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Products $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('products_index');
    }

    /**
     * Creates a form to delete a Products entity.
     *
     * @param Products $product The Products entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Products $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('products_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
