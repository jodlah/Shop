<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Orders;
use ShopBundle\Form\OrdersType;

/**
 * Orders controller.
 *
 * @Route("/orders")
 */
class OrdersController extends Controller
{
    /**
     * Lists all Orders entities.
     *
     * @Route("/", name="orders_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('ShopBundle:Orders')->findAll();

        return $this->render('ShopBundle:orders:index.html.twig', array(
            'orders' => $orders,
        ));
    }

    /**
     * Creates a new Orders entity.
     *
     * @Route("/new", name="orders_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $order = new Orders();
        $form = $this->createForm('ShopBundle\Form\OrdersType', $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('orders_show', array('id' => $order->getId()));
        }

        return $this->render('ShopBundle:orders:new.html.twig', array(
            'order' => $order,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Orders entity.
     *
     * @Route("/{id}", name="orders_show")
     * @Method("GET")
     */
    public function showAction(Orders $order)
    {
        $deleteForm = $this->createDeleteForm($order);

        return $this->render('ShopBundle:orders:show.html.twig', array(
            'order' => $order,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Orders entity.
     *
     * @Route("/{id}/edit", name="orders_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Orders $order)
    {
        $deleteForm = $this->createDeleteForm($order);
        $editForm = $this->createForm('ShopBundle\Form\OrdersType', $order);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('orders_edit', array('id' => $order->getId()));
        }

        return $this->render('ShopBundle:orders:edit.html.twig', array(
            'order' => $order,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Orders entity.
     *
     * @Route("/{id}", name="orders_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Orders $order)
    {
        $form = $this->createDeleteForm($order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($order);
            $em->flush();
        }

        return $this->redirectToRoute('orders_index');
    }

    /**
     * Creates a form to delete a Orders entity.
     *
     * @param Orders $order The Orders entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Orders $order)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('orders_delete', array('id' => $order->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
