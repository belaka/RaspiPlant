<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Sensor;
use RaspiPlant\Bundle\BoardBundle\Form\SensorType;
use RaspiPlant\Bundle\BoardBundle\Manager\SensorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Sensor controller.
 *
 */
class SensorController extends AbstractController
{
    /**
     * @param SensorManager $sensorManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(SensorManager $sensorManager)
    {
        $sensors = $sensorManager->findAll();

        return $this->render('sensor/index.html.twig', array(
            'sensors' => $sensors,
        ));
    }

    /**
     * @param Request $request
     * @param SensorManager $sensorManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, SensorManager $sensorManager)
    {
        $sensor = new Sensor();
        $form = $this->createForm(SensorType::class, $sensor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sensorManager->persist($sensor);
            $sensorManager->flush($sensor);

            return $this->redirectToRoute('sensor_show', array('id' => $sensor->getId()));
        }

        return $this->render('sensor/new.html.twig', array(
            'sensor' => $sensor,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param SensorManager $sensorManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(SensorManager $sensorManager, $id)
    {
        $sensor = $sensorManager->getRepository()->find($id);

        if (!($sensor instanceof Sensor)) {
            throw new NotFoundResourceException();
        }

        $deleteForm = $this->createDeleteForm($sensor);

        return $this->render('sensor/show.html.twig', array(
            'sensor' => $sensor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param SensorManager $sensorManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, SensorManager $sensorManager, $id)
    {
        $sensor = $sensorManager->getRepository()->find($id);

        if (!($sensor instanceof Sensor)) {
            throw new NotFoundResourceException();
        }

        $deleteForm = $this->createDeleteForm($sensor);
        $editForm = $this->createForm(SensorType::class, $sensor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $sensorManager->flush();

            return $this->redirectToRoute('sensor_edit', array('id' => $sensor->getId()));
        }

        return $this->render('sensor/edit.html.twig', array(
            'sensor' => $sensor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param SensorManager $sensorManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, SensorManager $sensorManager, $id)
    {
        $sensor = $sensorManager->getRepository()->find($id);

        if (!($sensor instanceof Sensor)) {
            throw new NotFoundResourceException();
        }

        $form = $this->createDeleteForm($sensor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sensorManager->remove($sensor);
            $sensorManager->flush($sensor);
        }

        return $this->redirectToRoute('sensor_index');
    }

    /**
     * @param Sensor $sensor
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Sensor $sensor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sensor_delete', array('id' => $sensor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
