<?php

namespace FullVibes\Bundle\BoardBundle\Model;

interface ActivableInterface
{
    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @return mixed
     */
    public function setActive($active);
}