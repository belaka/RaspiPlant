<?php

namespace RaspiPlant\Bundle\BoardBundle\Model;

interface SluggableInterface
{
    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug($slug);
}
