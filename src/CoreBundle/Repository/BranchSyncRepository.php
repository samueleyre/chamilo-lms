<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Repository;

use Doctrine\DBAL\Types\Types;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class BranchSyncRepository extends NestedTreeRepository
{
    /**
     * @param string $keyword
     */
    public function searchByKeyword($keyword)
    {
        $qb = $this->createQueryBuilder('a');

        //Selecting user info
        $qb->select('DISTINCT b');

        $qb->from('Chamilo\CoreBundle\Entity\BranchSync', 'b');

        //Selecting courses for users
        //$qb->innerJoin('u.courses', 'c');

        //@todo check app settings
        $qb->addOrderBy('b.branchName ASC');
        $qb->where('b.branchName LIKE :keyword');
        $qb->setParameter('keyword', "%{$keyword}%", Types::STRING);
        $q = $qb->getQuery();

        return $q->execute();
    }

    /**
     * Gets the first branch with parent_id = NULL.
     */
    public function getTopBranch()
    {
        $qb = $this->createQueryBuilder('a');

        //Selecting user info
        $qb->select('DISTINCT b');

        $qb->from('Chamilo\CoreBundle\Entity\BranchSync', 'b');
        $qb->where('b.parent IS NULL');
        $qb->orderBy('b.id', 'ASC');
        $qb->setMaxResults(1);
        $q = $qb->getQuery()->getResult();
        if (empty($q)) {
            return null;
        }
        foreach ($q as $result) {
            return $result;
        }
    }
}
