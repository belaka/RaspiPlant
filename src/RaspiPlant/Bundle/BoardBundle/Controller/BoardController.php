<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Board;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Board controller.
 *
 */
class BoardController extends Controller
{
    /**
     * Lists all board entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $boards = $em->getRepository('BoardBundle:Board')->findAll();

        return $this->render('board/index.html.twig', array(
            'boards' => $boards,
        ));
    }

    /**
     * Creates a new board entity.
     *
     */
    public function newAction(Request $request)
    {
        $board = new Board();
        $form = $this->createForm('RaspiPlant\Bundle\BoardBundle\Form\BoardType', $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($board);
            $em->flush($board);

            return $this->redirectToRoute('board_show', array('id' => $board->getId()));
        }

        return $this->render('board/new.html.twig', array(
            'board' => $board,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a board entity.
     *
     */
    public function showAction(Board $board)
    {
        $deleteForm = $this->createDeleteForm($board);

        return $this->render('board/show.html.twig', array(
            'board' => $board,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing board entity.
     *
     */
    public function editAction(Request $request, Board $board)
    {
        $deleteForm = $this->createDeleteForm($board);
        $editForm = $this->createForm('RaspiPlant\Bundle\BoardBundle\Form\BoardType', $board);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('board_edit', array('id' => $board->getId()));
        }

        return $this->render('board/edit.html.twig', array(
            'board' => $board,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a board entity.
     *
     */
    public function deleteAction(Request $request, Board $board)
    {
        $form = $this->createDeleteForm($board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($board);
            $em->flush($board);
        }

        return $this->redirectToRoute('board_index');
    }

    /**
     * Creates a form to delete a board entity.
     *
     * @param Board $board The board entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Board $board)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('board_delete', array('id' => $board->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
