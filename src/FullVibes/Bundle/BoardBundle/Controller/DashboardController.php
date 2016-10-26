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
        
        $temperatures = $this->getAnalyticsManager()->findByKeyAndDate('temperature', new \DateTime);
        $tempArray = [];
        
        foreach ($temperatures as $temperature) {
            $tempArray[] = [addslashes($temperature->getEventDate()->format('Y/m/d H:i:s')), round($temperature->getEventValue(), 2)];
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
