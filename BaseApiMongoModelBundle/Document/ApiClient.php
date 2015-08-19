<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\Document;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use OpenOrchestra\Mapping\Annotations as ORCHESTRA;

/**
 * Class ApiClient
 *
 * @ODM\Document(
 *   collection="api_client",
 *   repositoryClass="OpenOrchestra\BaseApiMongoModelBundle\Repository\ApiClientRepository"
 * )
 */
class ApiClient implements ApiClientInterface
{
    use Blockable;

    /**
     * @ODM\Id()
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $key;

    /**
     * @ODM\Field(type="string")
     */
    protected $secret;

    /**
     * @ODM\Field(type="string")
     * @ORCHESTRA\Search(key="name")
     */
    protected $name;

    /**
     * @ODM\Field(type="boolean")
     * @ORCHESTRA\Search(key="trusted", type="boolean")
     */
    protected $trusted;

    /**
     * @ODM\Field(type="collection")
     */
    protected $roles = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->key    = $this->generateId();
        $this->secret = $this->generateId();
    }

    /**
     * Generate an unique Id
     *
     * @return string
     */
    public function generateId()
    {
        $data = unpack('H*', openssl_random_pseudo_bytes(32));

        return array_pop($data);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isTrusted()
    {
        return $this->trusted;
    }

    /**
     * @param bool $trusted
     */
    public function setTrusted($trusted)
    {
        $this->trusted = $trusted;
    }

    /**
     * @param string $role
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * @param $role
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }
}
