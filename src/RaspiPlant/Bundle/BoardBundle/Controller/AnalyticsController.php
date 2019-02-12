<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Analytics;
use RaspiPlant\Bundle\BoardBundle\Manager\AnalyticsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Analytic controller.
 *
 */
class AnalyticsController extends AbstractController
{
    /**
     * @param AnalyticsManager $analyticsManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(AnalyticsManager $analyticsManager)
    {
        $analytics = $analyticsManager->findAll();

        return $this->render('analytics/index.html.twig', array(
            'analytics' => $analytics,
        ));
    }

    /**
     * @param Analytics $analytic
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Analytics $analytic)
    {

        return $this->render('analytics/show.html.twig', array(
            'analytic' => $analytic,
        ));
    }
}
