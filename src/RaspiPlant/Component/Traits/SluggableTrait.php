<?php

namespace RaspiPlant\Component\Traits;

/**
 * Trait SluggableTrait
 * @package RaspiPlant\Component\Traits
 */
trait SluggableTrait
{
    /**
     *
     * @var string
     */
    protected $slug;

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }
}
