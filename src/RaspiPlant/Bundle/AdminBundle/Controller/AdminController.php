<?php

namespace RaspiPlant\Bundle\AdminBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AnnotationChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\GanttChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\GaugeChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\OrgChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Timeline;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\UserBundle\Model\UserManagerInterface;
use RaspiPlant\Bundle\BoardBundle\Manager\BoardManager;
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

    /** @var  BoardManager */
    private $boardManager;

    /** @var  KernelInterface */
    private $kernel;

    public function __construct(UserManagerInterface $userManager, BoardManager $boardManager, KernelInterface $kernel)
    {
        $this->boardManager = $boardManager;
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

            $array = [
                [['v' => 'Boards', 'f' => '<div style="color:red; font-style:italic">Boards</div>'], '', 'Boards']
            ];

            $boards = $this->boardManager->findAll();

            foreach ($boards as $board) {
                array_push(
                    $array,
                    [
                        [
                            'v' => $board->getName(),
                            'f' => '<div ><a href="/admin/?entity=Board&action=show&id='. $board->getId() . '" class="text-success font-weight-bold">'.$board->getName().'</a></div>'
                        ],
                        'Boards',
                        $board->getName()
                    ]
                );
                array_push($array, [['v' => 'Sensors', 'f' => '<div style="color:red; font-style:italic">Sensors</div>'], $board->getName(), 'Sensors']);
                array_push($array, [['v' => 'Actuators', 'f' => '<div style="color:red; font-style:italic">Actuators</div>'], $board->getName(), 'Actuators']);
                array_push($array, [['v' => 'Displays', 'f' => '<div style="color:red; font-style:italic">Displays</div>'], $board->getName(), 'Displays']);
                array_push($array, [['v' => 'Communicators', 'f' => '<div style="color:red; font-style:italic">Communicators</div>'], $board->getName(), 'Communicators']);
                array_push($array, [['v' => 'Scripts', 'f' => '<div style="color:red; font-style:italic">Scripts</div>'], $board->getName(), 'Scripts']);

                foreach ($board->getSensors() as $sensor) {
                    array_push(
                        $array,
                        [
                            [
                                'v' => $sensor->getName(),
                                'f' => '<a href="/admin/?entity=Sensor&action=show&id='. $sensor->getId() . '" class="text-success font-weight-bold">'.$sensor->getName().'</a></div>'
                            ],
                            'Sensors',
                            $sensor->getName()
                        ]
                    );
                    array_push($array, [['v' => $sensor->getName() . ' Scripts', 'f' => '<div style="color:red; font-style:italic">Scripts</div>'], $sensor->getName(), $sensor->getName() . ' Scripts']);
                    foreach ($sensor->getScripts() as $sensorScript) {
                        array_push($array, [['v' => $sensorScript->getName(), 'f' => '<div ><a href="/admin/?entity=Script&action=show&id='. $sensorScript->getId() . '" class="text-success font-weight-bold">'.$sensorScript->getName().'</a></div>'], $sensorScript->getName() . ' Scripts', $sensorScript->getName()]);
                    }
                }

                foreach ($board->getActuators() as $actuator) {
                    array_push($array, [['v' => $actuator->getName(), 'f' => '<div ><a href="/admin/?entity=Actuator&action=show&id='. $actuator->getId() . '" class="text-success font-weight-bold">'.$actuator->getName().'</a></div>'], 'Actuators', $actuator->getName()]);
                    array_push($array, [['v' => $actuator->getName() . ' Scripts', 'f' => '<div style="color:red; font-style:italic">Scripts</div>'], $actuator->getName(), $actuator->getName() . ' Scripts']);
                    foreach ($actuator->getScripts() as $actuatorScript) {
                        array_push($array, [['v' => $actuatorScript->getName(), 'f' => '<div ><a href="/admin/?entity=Script&action=show&id='. $actuatorScript->getId() . '" class="text-success font-weight-bold">'.$actuatorScript->getName().'</a></div>'], $actuator->getName() . ' Scripts', $actuatorScript->getName()]);
                    }
                }

                foreach ($board->getScripts() as $script) {
                    array_push($array, [['v' => $script->getName(), 'f' => '<div ><a href="/admin/?entity=Script&action=show&id='. $script->getId() . '" class="text-success font-weight-bold">'.$script->getName().'</a></div>'], 'Scripts', $script->getName()]);
                }
            }

            $org = new OrgChart();
            $org->getData()->setArrayToDataTable($array, true);
            /**
            $org->getData()->setArrayToDataTable(
                [
                    [['v' => 'BoardName', 'f' => '<div style="color:#4dff2f; font-style:italic">BoardName</div>']],
                    [['v' => 'Sensors', 'f' => '<div style="color:red; font-style:italic">Sensors</div>'], 'BoardName', 'Sensors'],
                    [['v' => 'Actuators', 'f' => '<div style="color:red; font-style:italic">Actuators</div>'], 'BoardName', 'Actuators'],
                    [['v' => 'Displays', 'f' => '<div style="color:red; font-style:italic">Displays</div>'], 'BoardName', 'Displays'],
                    [['v' => 'Scripts', 'f' => '<div style="color:red; font-style:italic">Scripts</div>'], 'BoardName', 'Scripts'],
                    [['v' => 'Communicators', 'f' => '<div style="color:red; font-style:italic">Communicators</div>'], 'BoardName', 'Communicators'],
                    ['Sensor1', 'Sensors', ''],
                    ['Sensor2', 'Sensors', ''],
                    ['Actuator1', 'Actuators', ''],
                    ['Actuator2', 'Actuators', ''],
                    ['SensorScript1', 'Sensor1', '']
                ],
                true
            );
             **/
            $org->getOptions()->setAllowHtml(true);

            return $this->render(
                '@AdminBundle/Resources/views/default/dashboard.html.twig',
                array('chart' => $org)
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
