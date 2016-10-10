<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Trait Blockable
 */
trait Blockable
{
    /**
     * @var boolean $blocked
     *
     * @ODM\Field(type="boolean")
     */
    protected $blocked = false;

    /**
     * @return boolean
     */
    public function isBlocked()
    {
        return $this->blocked;
    }

    /**
     * @param boolean $blocked
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
    }

    /**
     * Block a client
     */
    public function block()
    {
        $this->blocked = true;
    }

    /**
     * Unblock a client
     */
    public function unblock()
    {
        $this->blocked = false;
    }
}
