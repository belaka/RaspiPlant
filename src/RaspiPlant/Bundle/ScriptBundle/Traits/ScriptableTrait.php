<?php

namespace RaspiPlant\Bundle\ScriptBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use RaspiPlant\Bundle\ScriptBundle\Entity\Script;

/**
 * Trait ScriptableTrait
 * @package RaspiPlant\Component\Traits
 */
trait ScriptableTrait
{

    /** @var ArrayCollection */
    protected $scripts;

    /**
     * @return ArrayCollection
     */
    public function getScripts() {
        return $this->scripts;
    }

    /**
     * @param ArrayCollection $scripts
     * @return $this
     */
    public function setScripts(ArrayCollection $scripts) {
        $this->scripts = $scripts;
        return $this;
    }

    /**
     * @param Script $script
     * @return $this
     */
    public function addScript(Script $script)
    {
        $this->scripts[] = $script;
        return $this;
    }
}
