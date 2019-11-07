<?php

namespace RaspiPlant\Component\Device\Communicator;

/**
 * Abstract Class for communicating with a communicator.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
abstract class AbstractCommunicator implements CommunicatorInterface
{

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

}
