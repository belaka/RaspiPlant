<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Entity\Analytics;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Analytic controller.
 *
 */
class AnalyticsController extends Controller
{
    /**
     * Lists all analytic entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $analytics = $em->getRepository('BoardBundle:Analytics')->findAll();

        return $this->render('analytics/index.html.twig', array(
            'analytics' => $analytics,
        ));
    }

    /**
     * Finds and displays a analytic entity.
     *
     */
    public function showAction(Analytics $analytic)
    {

        return $this->render('analytics/show.html.twig', array(
            'analytic' => $analytic,
        ));
    }
}
