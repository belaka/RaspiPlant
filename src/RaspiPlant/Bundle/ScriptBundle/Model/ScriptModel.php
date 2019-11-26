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
    protected $type;

    /** @var  */
    protected $script;

    /**
     * BoardModel constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct($data = array())
    {
        $this->type = 'command';
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $script
     */
    public function setScript($script): void
    {
        $this->script = $script;
    }

}
