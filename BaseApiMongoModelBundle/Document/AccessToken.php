<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\Document;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AccessToken
 *
 * @ODM\Document(
 *   collection="access_token",
 *   repositoryClass="OpenOrchestra\BaseApiMongoModelBundle\Repository\AccessTokenRepository"
 * )
 */
class AccessToken implements TokenInterface
{
    use Blockable;
    use Expireable;

    /**
     * @ODM\Id()
     */
    protected $id;

    /**
     * @var string $code
     *
     * @ODM\Field(type="string")
     */
    protected $code;

    /**
     * @var string $code
     *
     * @ODM\Field(type="string")
     */
    protected $refreshCode;

    /**
     * @var UserInterface $user
     *
     * @ODM\ReferenceOne(targetDocument="Symfony\Component\Security\Core\User\UserInterface")
     */
    protected $user;

    /**
     * @var ApiClientInterface $client
     *
     * @ODM\ReferenceOne(targetDocument="OpenOrchestra\BaseApi\Model\ApiClientInterface")
     */
    protected $client;

    /**
     * @ODM\Date
     */
    protected $createdAt;

    protected $violations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->code = $this->generateId();
        $this->refreshCode = $this->generateId();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getRefreshCode()
    {
        return $this->refreshCode;
    }

    /**
     * @param string $refreshCode
     */
    public function setRefreshCode($refreshCode)
    {
        $this->refreshCode = $refreshCode;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return ApiClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ApiClientInterface $client
     */
    public function setClient(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param ApiClientInterface $client
     * @param UserInterface      $user
     *
     * @return TokenInterface
     */
    public static function create(ApiClientInterface $client, UserInterface $user = null)
    {
        $accessToken = new self();
        $accessToken->setUser($user);
        $accessToken->setClient($client);

        return $accessToken;
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @return boolean
     */
    public function isValid(ValidatorInterface $validator)
    {
        $this->violations = $validator->validate($this);

        return 0 === count($this->violations);
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
        return $this->violations;
    }
}
