<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use RaspiPlant\Component\WiringPi\WiringPi;

class GpioController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gpioAction()
    {
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
            $exception = new ProcessFailedException($process);
            $this->addFlash(
                'error',
                $exception->getMessage()
            );
        }

        $gpioTable = explode(PHP_EOL, $process->getOutput());

        $response = "";// "<pre>" . print_r($gpioTable, 1) . "</pre>\n";

        for ($i = 3; $i < (count($gpioTable) - 4); $i++) {

            $response .= '<tr class="' . (($even) ? 'even' : 'odd') . '">';

            $row = array_map(function($v){ return trim($v);}, explode('|', $gpioTable[$i]));

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
            $response .= '<td>' . intval(trim($row[5])) . '</td>';
            //Physical
            $response .= '<td>' . trim($row[6]) . '</td>';
            //Physical
            $response .= '<td>' . trim($row[8]) . '</td>';
            //Value
            $response .= '<td>' . intval(trim($row[9])) . '</td>';
            //Mode
            $response .= '<td>' . trim($row[10]) . '</td>';
            //Name
            $response .= '<td>' . trim($row[11]) . '</td>';
            //Wpi
            $response .= '<td>' . trim($row[12]) . '</td>';
            //BCM
            $response .= '<td>' . trim($row[13]) . '</td>';

            //$response .= '<td class="' . (($mode == 'IN') ? 'orange' : 'blue') . '"><a href="?c=pm&p=' . $pin . '&v=' . (($mode == 'IN') ? '1' : '0') . '">' . $mode . '</a></td>';
            //$response .= '<td class="' . (($value == 'High') ? 'green' : 'red') . '"><a href="?c=dw&p=' . $pin . '&v=' . (($value == 'High') ? '0' : '1') . '">' . $value . '</a></td>';
            $response .= '</tr>';

            $even = !$even;
        }

        return $this->render(
                'gpio/gpio.html.twig',
                array(
                    'gpioTable' => $response
                )
        );

    }

}
