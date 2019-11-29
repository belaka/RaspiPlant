<?php

namespace RaspiPlant\Bundle\AdminBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\GanttChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Timeline;
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

            $gantt = new GanttChart();
            $gantt->getData()->setArrayToDataTable([
                [['label' => 'Task ID', 'type' => 'string'], ['label' => 'Task Name', 'type' => 'string'],
                    ['label' => 'Resource', 'type' => 'string'], ['label' => 'Start Date', 'type' => 'date'],
                    ['label' => 'End Date', 'type' => 'date'], ['label' => 'Duration', 'type' => 'number'],
                    ['label' => 'Percent Complete', 'type' => 'number'], ['label' => 'Dependencies', 'type' => 'string']],
                ['2014Spring', 'Spring 2014', 'spring',
                    new \DateTime('2014-02-22'), new \DateTime('2014-05-20'), null, 100, null],
                ['2014Summer', 'Summer 2014', 'summer',
                    new \DateTime('2014-05-21'), new \DateTime('2014-08-20'), null, 100, null],
                ['2014Autumn', 'Autumn 2014', 'autumn',
                    new \DateTime('2014-08-21'), new \DateTime('2014-11-20'), null, 100, null],
                ['2014Winter', 'Winter 2014', 'winter',
                    new \DateTime('2014-11-21'), new \DateTime('2015-02-21'), null, 100, null],
                ['2015Spring', 'Spring 2015', 'spring',
                    new \DateTime('2015-2-22'), new \DateTime('2015-5-20'), null, 50, null],
                ['2015Summer', 'Summer 2015', 'summer',
                    new \DateTime('2015-5-21'), new \DateTime('2015-8-20'), null, 0, null],
                ['2015Autumn', 'Autumn 2015', 'autumn',
                    new \DateTime('2015-8-21'), new \DateTime('2015-11-20'), null, 0, null],
                ['2015Winter', 'Winter 2015', 'winter',
                    new \DateTime('2015-11-21'), new \DateTime('2016-2-21'), null, 0, null],
                ['Football', 'Football Season', 'sports',
                    new \DateTime('2014-8-4'), new \DateTime('2015-1-1'), null, 100, null],
                ['Baseball', 'Baseball Season', 'sports',
                    new \DateTime('2015-2-31'), new \DateTime('2015-9-20'), null, 14, null],
                ['Basketball', 'Basketball Season', 'sports',
                    new \DateTime('2014-9-28'), new \DateTime('2015-5-20'), null, 86, null],
                ['Hockey', 'Hockey Season', 'sports',
                    new \DateTime('2014-9-8'), new \DateTime('2015-5-21'), null, 89, null]
            ]);
            $gantt->getOptions()->setHeight(400);
            $gantt->getOptions()->getGantt()->setTrackHeight(30);
            $gantt->getOptions()->setWidth(900);

            return $this->render(
                '@AdminBundle/Resources/views/default/dashboard.html.twig',
                array('cal' => $gantt)
            );
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
