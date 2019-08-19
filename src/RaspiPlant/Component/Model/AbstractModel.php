<?php

namespace RaspiPlant\Component\Model;

use RaspiPlant\Component\Traits\UtilsTrait;

/**
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
abstract class AbstractModel
{
    use UtilsTrait;

    /**
     * AbstractModel constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct($data = array())
    {
        $properties = $this->getProperties();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $k = lcfirst($this->camelize($key));
                if (in_array($k, $properties)) {
                    $method = 'set' . ucfirst($k);
                    $this->$method($value);
                }
            }
        }
    }


    /**
     * @return array
     * @throws \ReflectionException
     */
    protected function getProperties()
    {
        $properties = array();

        $reflect = new \ReflectionClass($this);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PROTECTED) as $prop) {
            $properties[$prop->getName()] = $prop->getName();
        }

        return $properties;
    }

    /**
     * Camelizes a string.
     *
     * @param string $id A string to camelize
     *
     * @return string The camelized string
     */
    public static function camelize($id)
    {
        return preg_replace_callback(
            '/(^|_|\.)+(.)/',
            function ($match) {
                return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
            },
            $id
        );
    }

    /**
     * LifeCycleCallback set date when needed
     */
    protected function dateCallback()
    {
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        } else {
            $this->setUpdatedAt(new \DateTime('now'));
        }
    }

    /**
     * LifeCycleCallback generate slug from $field
     *
     * @param string $field
     */
    public function generateSlug($field)
    {
        if(!method_exists($this, 'setSlug')) {
            return;
        }

        $method = 'get' . ucfirst($field);
        $this->setSlug($this->makeSlug($this->$method()));
    }

}
