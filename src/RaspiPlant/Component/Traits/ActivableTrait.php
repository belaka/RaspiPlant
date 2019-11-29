<?php

namespace RaspiPlant\Component\Traits;

/**
 * Trait ActivableTrait
 * @package RaspiPlant\Component\Traits
 */
trait ActivableTrait
{

    /**
     * @var boolean
     */
    protected $active = false;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }


    /**
     * @param bool $active
     * @return mixed
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }


}
