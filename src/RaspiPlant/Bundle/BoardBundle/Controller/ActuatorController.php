<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Actuator;
use RaspiPlant\Bundle\BoardBundle\Form\ActuatorType;
use RaspiPlant\Bundle\BoardBundle\Manager\ActuatorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Actuator controller.
 *
 */
class ActuatorController extends AbstractController
{
    /**
     * @param ActuatorManager $actuatorManager
     * @return Response
     */
    public function indexAction(ActuatorManager $actuatorManager)
    {
        $actuators = $actuatorManager->findAll();

        return $this->render('actuator/index.html.twig', array(
            'actuators' => $actuators,
        ));
    }

    /**
     * @param Request $request
     * @param ActuatorManager $actuatorManager
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request, ActuatorManager $actuatorManager)
    {
        $actuator = new Actuator();
        $form = $this->createForm(ActuatorType::class, $actuator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actuatorManager->persist($actuator);
            $actuatorManager->flush($actuator);

            return $this->redirectToRoute('actuator_show', array('id' => $actuator->getId()));
        }

        return $this->render('actuator/new.html.twig', array(
            'actuator' => $actuator,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param ActuatorManager $actuatorManager
     * @param $id
     * @return Response
     */
    public function showAction(ActuatorManager $actuatorManager, $id)
    {
        $actuator = $actuatorManager->getRepository()->find($id);

        if (!($actuator instanceof Actuator)) {
            throw new NotFoundResourceException();
        }

        $deleteForm = $this->createDeleteForm($actuator);

        return $this->render('actuator/show.html.twig', array(
            'actuator' => $actuator,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param ActuatorManager $actuatorManager
     * @param $id
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, ActuatorManager $actuatorManager, $id)
    {
        $actuator = $actuatorManager->getRepository()->find($id);

        if (!($actuator instanceof Actuator)) {
            throw new NotFoundResourceException();
        }

        $deleteForm = $this->createDeleteForm($actuator);
        $editForm = $this->createForm(ActuatorType::class, $actuator);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $actuatorManager->flush();

            return $this->redirectToRoute('actuator_edit', array('id' => $actuator->getId()));
        }

        return $this->render('actuator/edit.html.twig', array(
            'actuator' => $actuator,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param ActuatorManager $actuatorManager
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, ActuatorManager $actuatorManager, $id)
    {
        $actuator = $actuatorManager->getRepository()->find($id);

        if (!($actuator instanceof Actuator)) {
            throw new NotFoundResourceException();
        }

        $form = $this->createDeleteForm($actuator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actuatorManager->remove($actuator);
            $actuatorManager->flush($actuator);
        }

        return $this->redirectToRoute('actuator_index');
    }

    /**
     * @param Request $request
     * @param ActuatorManager $actuatorManager
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateAction(Request $request, ActuatorManager $actuatorManager, $id)
    {
        $actuator = $actuatorManager->getRepository()->find($id);

        if (!($actuator instanceof Actuator)) {
            throw new NotFoundResourceException();
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
     * @param Actuator $actuator
     * @return FormInterface
     */
    private function createDeleteForm(Actuator $actuator)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actuator_delete', array('id' => $actuator->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}