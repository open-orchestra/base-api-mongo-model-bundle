<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\Repository;

use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Repository\ApiClientRepositoryInterface;
use OpenOrchestra\Repository\AbstractAggregateRepository;


/**
 * Class ApiClientRepository
 */
class ApiClientRepository extends AbstractAggregateRepository implements ApiClientRepositoryInterface
{
    /**
     * @param string $key
     * @param string $secret
     *
     * @return ApiClientInterface
     */
    public function findOneByKeyAndSecret($key, $secret)
    {
        return $this->findOneBy(array('key' => $key, 'secret' => $secret));
    }
}
