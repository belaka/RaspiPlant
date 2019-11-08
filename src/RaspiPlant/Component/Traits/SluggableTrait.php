<?php

namespace RaspiPlant\Component\Traits;

/**
 * Sluggable entities trait.
 *
 * @author Vincent Honnorat <vincenth@effi-net.com>
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
