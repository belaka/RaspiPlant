<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Device;
use RaspiPlant\Bundle\BoardBundle\Form\DeviceType;
use RaspiPlant\Bundle\BoardBundle\Manager\DeviceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Device controller.
 *
 */
class DeviceController extends AbstractController
{
    /**
     * @param DeviceManager $deviceManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(DeviceManager $deviceManager)
    {
        $devices = $deviceManager->findAll();

        return $this->render('device/index.html.twig', array(
            'devices' => $devices,
        ));
    }

    /**
     * Creates a new device entity.
     *
     */
    public function newAction(Request $request)
    {
        $device = new Device();
        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($device);
            $em->flush($device);

            return $this->redirectToRoute('device_show', array('id' => $device->getId()));
        }

        return $this->render('device/new.html.twig', array(
            'device' => $device,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a device entity.
     *
     */
    public function showAction(Device $device)
    {
        $deleteForm = $this->createDeleteForm($device);

        return $this->render('device/show.html.twig', array(
            'device' => $device,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing device entity.
     *
     */
    public function editAction(Request $request, Device $device)
    {
        $deleteForm = $this->createDeleteForm($device);
        $editForm = $this->createForm(DeviceType::class, $device);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('device_edit', array('id' => $device->getId()));
        }

        return $this->render('device/edit.html.twig', array(
            'device' => $device,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param DeviceManager $deviceManager
     * @param Device $device
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(Request $request, DeviceManager $deviceManager, Device $device)
    {
        $form = $this->createDeleteForm($device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deviceManager->remove($device);
            $deviceManager->flush($device);
        }

        return $this->redirectToRoute('device_index');
    }

    /**
     * @param Device $device
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Device $device)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('device_delete', array('id' => $device->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
