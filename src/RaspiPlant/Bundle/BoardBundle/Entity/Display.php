<?php

namespace RaspiPlant\Bundle\BoardBundle\Entity;

use Cocur\Slugify\Slugify;
use RaspiPlant\Bundle\BoardBundle\Model\DisplayModel;

/*
 * Display Entity
 */

/**
 * Description of Analytics
 *
 * @author belaka
 */
class Display extends DisplayModel
{

    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     * @return \RaspiPlant\Bundle\BoardBundle\Entity\Display
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     */
    public function prePersist()
    {
        if (!empty($this->name)) {
            $slugify = new Slugify(array('separator' => '_'));
            $this->setSlug($slugify->slugify($this->name));
        }
    }

    /**
     *
     */
    public function preUpdate()
    {
        if (!empty($this->name)) {
            $slugify = new Slugify(array('separator' => '_'));
            $this->setSlug($slugify->slugify($this->name));
        }
    }

}
