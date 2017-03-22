<?php

namespace FullVibes\Bundle\BoardBundle\Model;

interface ActivableInterface
{
    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @param $active
     * @return mixed
     */
    public function setActive($active);
}