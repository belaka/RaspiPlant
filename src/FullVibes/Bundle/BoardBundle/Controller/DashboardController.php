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
        
        $date = new \DateTime;
        $date->modify('-30 minutes');
        $temperatures = $this->getAnalyticsManager()->findByKeyAndDate('temperature', $date);
        $tempArray = [];
        
        foreach ($temperatures as $temperature) {
            $tempValue = round($temperature->getEventValue(), 2);
            if ($tempValue > 0 && $tempValue < 90) {
                $tempArray[] = [addslashes($temperature->getEventDate()->format('H:i')), round($tempValue, 2)];
            }
        }
        
        
        //$tempArray = [["02/2013",1500],["03/2013",2500],["04/2013",1700],["05/2013",800],["06/2013",1500],["07/2013",2350],["08/2013",1500],["09/2013",1300],["10/2013",4600]];
                
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array('data' => $data, 'date' => $date, 'temperatures' => json_encode($tempArray, JSON_UNESCAPED_SLASHES))
        );
    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
}
