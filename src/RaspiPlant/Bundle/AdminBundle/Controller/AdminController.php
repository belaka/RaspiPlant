<?php

namespace RaspiPlant\Bundle\AdminBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\UserBundle\Model\UserManagerInterface;
use RaspiPlant\Bundle\ScriptBundle\Command\ScriptCommandInterface;
use RaspiPlant\Component\Event\BoardEvents;
use RaspiPlant\Component\Event\DeviceEvents;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class AdminController extends EasyAdminController
{
    /** @var UserManagerInterface  */
    private $userManager;

    /** @var  KernelInterface */
    private $kernel;

    public function __construct(UserManagerInterface $userManager, KernelInterface $kernel)
    {
        $this->userManager = $userManager;
        $this->kernel = $kernel;
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

    public function createScriptEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        $scripts = $this->getScripts();
        $events = $this->getEvents();

        $formBuilder
            ->add('script', ChoiceType::class, [
            'choices'  => array_combine(
                $scripts,
                $scripts
            )
        ])
            ->add('event', ChoiceType::class, [
            'choices'  => array_combine(
                $events,
                $events
            )
        ]);

        return $formBuilder;
    }

    private function getScripts()
    {
        $scripts = [];

        $application = new Application($this->kernel);

        $classes = $application->all('raspiplant:script');

        foreach ($classes as $key => $class) {
            if (in_array(ScriptCommandInterface::class , class_implements($class))) {
                $actions = $class::getActions();
                foreach ($actions as $action) {
                    $scripts[get_class($class) . $action] = $key . ' ' . $action;
                }
            }
        }

        return $scripts;
    }

    private function getEvents()
    {
        $events = [];

        $boardEvents = BoardEvents::getEvents();
        $deviceEvents = DeviceEvents::getEvents();

        $events = array_merge($boardEvents, $deviceEvents);

        return $events;
    }
}
