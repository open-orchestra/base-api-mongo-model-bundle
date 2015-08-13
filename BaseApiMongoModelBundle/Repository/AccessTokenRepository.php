<?php

namespace OpenOrchestra\BaseApiMongoModelBundle\Repository;

use OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use OpenOrchestra\Repository\AbstractAggregateRepository;

/**
 * Class AccessTokenRepository
 */
class AccessTokenRepository extends AbstractAggregateRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function findOneByClientWithoutUser(ApiClientInterface $client)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('client.id')->equals($client->getId());
        $qb->field('user')->equals(null);
        $qb->sort('createdAt', 'desc');
        $qb->limit(1);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param ApiClientInterface $client
     * @param string $refreshToken
     *
     * @return TokenInterface
     */
    public function findOneByClientAndRefreshToken(ApiClientInterface $client, $refreshToken)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('client.id')->equals($client->getId());
        $qb->field('refreshCode')->equals($refreshToken);
        $qb->limit(1);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param string $token
     *
     * @return TokenInterface
     */
    public function findOneByCode($token)
    {
        return $this->findOneBy(array('code' => $token));
    }
}
