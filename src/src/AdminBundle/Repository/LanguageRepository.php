<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Language;

/**
 * LanguageRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LanguageRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLanguagesFromAutocomplete($term)
    {
        $qb = $this->_em->createQueryBuilder('l');
        $qb->select('l')
            ->from(Language::class, 'l')
            ->where('l.language LIKE :language')
            ->setParameter('language', '%'.$term.'%');

        return $qb->getQuery()->getResult();
    }
}
