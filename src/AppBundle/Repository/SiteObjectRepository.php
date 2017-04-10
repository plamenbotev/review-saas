<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Site;
use Doctrine\ORM\NoResultException;

/**
 * SiteObjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteObjectRepository extends \Doctrine\ORM\EntityRepository
{

    public function retrieveObjectByString(Site $site, $object){

        if(empty($object)){
            return null;
        }

        $hash = md5(urldecode(trim($object)));

        $qb = $this->createQueryBuilder("so");

        $qb->select("so");

        $qb->where( $qb->expr()->eq('so.hash', '?1'));
        $qb->andWhere($qb->expr()->eq('so.site', '?2'));
        $qb->setParameter(1, $hash);
        $qb->setParameter(2, $site->getId());

        try {

            return $qb->getQuery()->getSingleResult();

        }catch (NoResultException $e){

            return null;

        }

    }



}
