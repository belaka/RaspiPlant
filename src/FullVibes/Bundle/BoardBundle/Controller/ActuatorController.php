<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use FullVibes\Bundle\BoardBundle\Entity\Actuator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Actuator controller.
 *
 */
class ActuatorController extends Controller
{
    /**
     * Lists all actuator entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $actuators = $em->getRepository('BoardBundle:Actuator')->findAll();

        return $this->render('actuator/index.html.twig', array(
            'actuators' => $actuators,
        ));
    }

    /**
     * Creates a new actuator entity.
     *
     */
    public function newAction(Request $request)
    {
        $actuator = new Actuator();
        $form = $this->createForm('FullVibes\Bundle\BoardBundle\Form\ActuatorType', $actuator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($actuator);
            $em->flush($actuator);

            return $this->redirectToRoute('actuator_show', array('id' => $actuator->getId()));
        }

        return $this->render('actuator/new.html.twig', array(
            'actuator' => $actuator,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a actuator entity.
     *
     */
    public function showAction(Actuator $actuator)
    {
        $deleteForm = $this->createDeleteForm($actuator);

        return $this->render('actuator/show.html.twig', array(
            'actuator' => $actuator,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing actuator entity.
     *
     */
    public function editAction(Request $request, Actuator $actuator)
    {
        $deleteForm = $this->createDeleteForm($actuator);
        $editForm = $this->createForm('FullVibes\Bundle\BoardBundle\Form\ActuatorType', $actuator);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actuator_edit', array('id' => $actuator->getId()));
        }

        return $this->render('actuator/edit.html.twig', array(
            'actuator' => $actuator,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a actuator entity.
     *
     */
    public function deleteAction(Request $request, Actuator $actuator)
    {
        $form = $this->createDeleteForm($actuator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($actuator);
            $em->flush($actuator);
        }

        return $this->redirectToRoute('actuator_index');
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateAction(Request $request, $id)
    {
        $actuatorManager = $this->getActuatorManager();
        $actuator = $actuatorManager->find($id);

        if (!($actuator instanceof Actuator)) {
            throw new \Exception("Actuator not found.");
        }

        $state = 0;
        $checkbox = $request->request->get('checkbox');

        if ($checkbox === "on") {
            $state = 1;
        }

        $actuator->setState($state);
        $actuatorManager->save($actuator);

        return new JsonResponse(array('success' => true), 200);
    }

    /**
     * Creates a form to delete a actuator entity.
     *
     * @param Actuator $actuator The actuator entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Actuator $actuator)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actuator_delete', array('id' => $actuator->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function getActuatorManager()
    {
        return $this->container->get("board.manager.actuator_manager");
    }
}
