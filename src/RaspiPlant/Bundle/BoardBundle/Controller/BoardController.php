<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Board;
use RaspiPlant\Bundle\BoardBundle\Form\BoardType;
use RaspiPlant\Bundle\BoardBundle\Manager\BoardManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Board controller.
 *
 */
class BoardController extends AbstractController
{
    /**
     * @param BoardManager $boardManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(BoardManager $boardManager)
    {
        $boards = $boardManager->findAll();

        return $this->render('board/index.html.twig', array(
            'boards' => $boards,
        ));
    }

    /**
     * @param Request $request
     * @param BoardManager $boardManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, BoardManager $boardManager)
    {
        $board = new Board();
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardManager->persist($board);
            $boardManager->flush($board);

            return $this->redirectToRoute('board_show', array('id' => $board->getId()));
        }

        return $this->render('board/new.html.twig', array(
            'board' => $board,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param BoardManager $boardManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(BoardManager $boardManager, $id)
    {
        $board = $boardManager->getRepository()->find($id);

        if (!($board instanceof Board)) {
            throw new NotFoundResourceException();
        }

        $deleteForm = $this->createDeleteForm($board);

        return $this->render('board/show.html.twig', array(
            'board' => $board,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param BoardManager $boardManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, BoardManager $boardManager, $id)
    {
        $board = $boardManager->getRepository()->find($id);

        if (!($board instanceof Board)) {
            throw new NotFoundResourceException();
        }

        $deleteForm = $this->createDeleteForm($board);
        $editForm = $this->createForm(BoardType::class, $board);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $boardManager->flush();

            return $this->redirectToRoute('board_edit', array('id' => $board->getId()));
        }

        return $this->render('board/edit.html.twig', array(
            'board' => $board,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param BoardManager $boardManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, BoardManager $boardManager, $id)
    {
        $board = $boardManager->getRepository()->find($id);

        if (!($board instanceof Board)) {
            throw new NotFoundResourceException();
        }

        $form = $this->createDeleteForm($board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardManager->remove($board);
            $boardManager->flush($board);
        }

        return $this->redirectToRoute('board_index');
    }

    /**
     * @param Board $board
     * @return \Symfony\Component\Form\FormInterface
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
