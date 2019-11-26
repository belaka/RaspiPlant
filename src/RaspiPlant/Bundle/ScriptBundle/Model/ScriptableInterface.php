<?php

namespace RaspiPlant\Bundle\ScriptBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use RaspiPlant\Bundle\ScriptBundle\Entity\Script;

interface ScriptableInterface
{
    /**
     * @return ArrayCollection
     */
    public function getScripts();

    /**
     * @param ArrayCollection $scripts
     * @return $this
     */
    public function setScripts(ArrayCollection $scripts);

    /**
     * @param Script $script
     * @return $this
     */
    public function addScript(Script $script);
}
