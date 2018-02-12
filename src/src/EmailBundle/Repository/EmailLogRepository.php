<?php

namespace EmailBundle\Repository;

use EmailBundle\Entity\EmailLog;

/**
 * EmailLogRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmailLogRepository extends \Doctrine\ORM\EntityRepository
{
    public function isEmailSent(
        $templateName,
        $html,
        $plain,
        $email,
        $meta,
        $emailType)
    {
        $qb = $this->_em->createQueryBuilder('el');
        $qb->select('count(el.id)')
            ->from(EmailLog::class, 'el')
            ->where('el.emailAddress LIKE :email')
            ->andWhere('el.emailType LIKE :type')
            ->andWhere('el.emailMeta LIKE :meta')
            ->andWhere('el.contentPlain LIKE :plain')
            ->andWhere('el.contentHtml LIKE :html')
            ->andWhere('el.template LIKE :template')
            ->andWhere('el.status = 0')
            ->setParameters(array(
                'email' => $email,
                'type' => $emailType,
                'meta' => $meta,
                'plain' => $plain,
                'html' => $html,
                'template' => $templateName
            ));

        $result = $qb->getQuery()->getSingleScalarResult();
        return $result > 0;
    }
}
