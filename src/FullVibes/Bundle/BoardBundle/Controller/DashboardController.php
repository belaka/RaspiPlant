<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $keys = array('temperature','humidity','light','air_quality', 'moisture');
        $lastEvents = $this->getAnalyticsManager()->getRepository()->findBy(array('eventKey' => $keys), array('eventDate' => 'DESC'), count($keys));
        $data = [];
        
        foreach ($lastEvents as $analytic)  {
            $data[$analytic->getEventKey()] = round($analytic->getEventValue(),2);
            $date = $analytic->getEventDate();
        }
        
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array('data' => $data, 'date' => $date)
        );
    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
}
