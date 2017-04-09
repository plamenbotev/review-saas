<?php

namespace AppBundle\Repository;

use Doctrine\ORM\NoResultException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Retrieve user by mail.
     * @param mail string
     * @return Site|null
     */
    public function retrieveUserByMail($mail){

        $qb = $this->createQueryBuilder("u");

        $qb->select("u");


        $qb->where( $qb->expr()->eq('u.email', '?1'));
        $qb->setParameter(1, $mail);

        try {

            return $qb->getQuery()->getSingleResult();

        }catch (NoResultException $e){

            return null;

        }



    }


}
