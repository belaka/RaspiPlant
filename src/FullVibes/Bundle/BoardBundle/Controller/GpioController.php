<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use FullVibes\Component\WiringPi\WiringPi;
use Symfony\Component\HttpFoundation\Request;

class GpioController extends Controller
{
    public function indexAction(Request $request)
    {
        //$pins = Array(0, 1, 2, 3, 4, 5, 6, 7, 17, 18, 19, 20);
        
        if (isset($_GET['c'])) {
                if ($_GET['c'] == 'pm') {
                        WiringPi::pinMode($_GET['p'], $_GET['v']);
                }
                if ($_GET['c'] == 'dw') {
                        WiringPi::digitalWrite($_GET['p'], $_GET['v']);
                }
        }
        
        $even = false;
        $process = new Process('gpio readall');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $gpioTable = explode(PHP_EOL, $process->getOutput());
        
        $response = "<pre>" . print_r($gpioTable, 1) . "</pre>\n";
        
        $gpioArray = array();
        
        for ($i = 3; $i < (count($gpioArray) - 3); $i++) {
            $response .= '<tr class="' . (($even) ? 'even' : 'odd') . '">';
            
            $row = array_map(function($v){ return trim($v);}, explode('|', $gpioArray[$i]));
//            $row = explode('|', $readall[$i]);
//            $pin = intval(trim($row[1]));
//            if (in_array($pin, $pins)) {
//                $mode = trim($row[5]);
//                $value = trim($row[6]);
//
            $response .= '<tr class="' . (($even) ? 'even' : 'odd') . '">';
            //BCM
            $response .= '<td>' . trim($row[1]) . '</td>';
            //Wpi
            $response .= '<td>' . trim($row[2]) . '</td>';
            //Name 
            $response .= '<td>' . trim($row[3]) . '</td>';
            //Mode
            $response .= '<td>' . trim($row[4]) . '</td>';
            //Value
            $response .= '<td>' . trim($row[5]) . '</td>';
            //Physical 
            $response .= '<td>' . trim($row[6]) . '</td>';
            //Physical 
            $response .= '<td>' . trim($row[7]) . '</td>';
            //Value
            $response .= '<td>' . trim($row[8]) . '</td>';
            //Mode
            $response .= '<td>' . trim($row[9]) . '</td>';
            //Name 
            $response .= '<td>' . trim($row[10]) . '</td>';
            //Wpi
            $response .= '<td>' . trim($row[11]) . '</td>';
            //BCM
            $response .= '<td>' . trim($row[12]) . '</td>';
            
            
            //$response .= '<td class="' . (($mode == 'IN') ? 'orange' : 'blue') . '"><a href="?c=pm&p=' . $pin . '&v=' . (($mode == 'IN') ? '1' : '0') . '">' . $mode . '</a></td>';
            //$response .= '<td class="' . (($value == 'High') ? 'green' : 'red') . '"><a href="?c=dw&p=' . $pin . '&v=' . (($value == 'High') ? '0' : '1') . '">' . $value . '</a></td>';
            $response .= '</tr>';
//
//                $even = !$even;
//            }
        }
        
        return $this->render(
                'BoardBundle:Gpio:gpio.html.twig',
                array(
                    'gpioTable' => $response
                )
        );        
        
    }
    
}
