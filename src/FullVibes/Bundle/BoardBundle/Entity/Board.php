<?php

namespace FullVibes\Bundle\BoardBundle\Entity;

use FullVibes\Bundle\BoardBundle\Model\BoardModel;

/*
 * Board Entity
 */

/**
 * Description of Board modelclass
 *
 * @author belaka
 */
class Board extends BoardModel {

    /**
     *
     * @var int
     */
    protected $id;

    /**
     * 
     * @param int $id
     * @return \FullVibes\Bundle\BoardBundle\Entity\Board
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function prePersist()
    {
        if (!empty($this->name)) {
            $this->slug = $this->makeSlug($this->name);
        }
    }

    public function preUpdate()
    {
        if (!empty($this->name)) {
            $this->slug = $this->makeSlug($this->name);
        }
    }


}
