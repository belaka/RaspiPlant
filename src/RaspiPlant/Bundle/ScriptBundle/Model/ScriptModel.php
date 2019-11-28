<?php

namespace RaspiPlant\Bundle\ScriptBundle\Model;

use RaspiPlant\Bundle\BoardBundle\Model\ActivableInterface;
use RaspiPlant\Bundle\BoardBundle\Model\SluggableInterface;
use RaspiPlant\Component\Model\AbstractModel;
use RaspiPlant\Component\Traits\ActivableTrait;
use RaspiPlant\Component\Traits\SluggableTrait;

class ScriptModel extends AbstractModel implements ActivableInterface, SluggableInterface
{
    use ActivableTrait, SluggableTrait;

    /** @var string */
    protected $name;

    /** @var string */
    protected $event;

    /** @var string */
    protected $script;

    /**
     * BoardModel constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @return mixed
     */
    public function getScript(): ?string
    {
        return $this->script;
    }

    /**
     * @param string $event
     */
    public function setEvent(string $event): void
    {
        $this->event = $event;
    }

    /**
     * @param mixed $script
     */
    public function setScript($script): void
    {
        $this->script = $script;
    }

}
