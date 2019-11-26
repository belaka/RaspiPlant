<?php

namespace RaspiPlant\Bundle\AdminBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends EasyAdminController
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            return $this->render('@AdminBundle/Resources/views/default/dashboard.html.twig');
        }

        return parent::indexAction($request);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction(Request $request)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            return $this->render('@AdminBundle/Resources/views/default/homepage.html.twig');
        }

        return parent::indexAction($request);
    }

    public function createNewUserEntity()
    {
        return $this->userManager->createUser();
    }

    public function persistUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
        parent::updateEntity($user);
    }

    public function createSensorEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        $sensors = $this->getParameter('device.sensors');
        $sensorKeys = str_replace(
            'RaspiPlant\Component\Device\Sensor\\', "", $sensors);

        $formBuilder->add('class', ChoiceType::class, [
            'choices'  => array_combine(
                $sensorKeys,
                $sensors
            )
        ]);

        return $formBuilder;
    }

    public function createActuatorEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        $actuators = $this->getParameter('device.actuators');
        $actuatorKeys = str_replace(
            'RaspiPlant\Component\Device\Actuator\\', "", $actuators);

        $formBuilder->add('class', ChoiceType::class, [
            'choices'  => array_combine(
                $actuatorKeys,
                $actuators
            )
        ]);

        return $formBuilder;
    }

    public function createCommunicatorEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        $communicators = $this->getParameter('device.communicators');
        $communicatorKeys = str_replace(
            'RaspiPlant\Component\Device\Communicator\\', "", $communicators);

        $formBuilder->add('class', ChoiceType::class, [
            'choices'  => array_combine(
                $communicatorKeys,
                $communicators
            )
        ]);

        return $formBuilder;
    }

    public function createDisplayEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        $display = $this->getParameter('device.displays');
        $displayKeys = str_replace(
            'RaspiPlant\Component\Device\Display\\', "", $display);

        $formBuilder->add('class', ChoiceType::class, [
            'choices'  => array_combine(
                $displayKeys,
                $display
            )
        ]);

        return $formBuilder;
    }
}
