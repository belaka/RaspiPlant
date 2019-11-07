<?php

namespace RaspiPlant\Component\Traits;

/**
 * Activable entities trait.
 *
 * @author Vincent Honnorat <vincenth@effi-net.com>
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
