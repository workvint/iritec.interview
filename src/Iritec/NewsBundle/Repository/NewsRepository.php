<?php

namespace Iritec\NewsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NewsRepository
 *
 */
class NewsRepository extends EntityRepository
{
    /**
     * Find items by paginator options
     * 
     * @param int $page Page number
     * @param int $limit Items on page
     * @param string $sort The ordering expression
     * @param string $order The ordering direction
     * @return array
     */
    public function findAllByPaginator($page, $limit, $sort, $order) 
    {       
        $offset = $limit * ($page - 1);
        
        return $this->findBy(array(), array($sort => $order), $limit, $offset);
    }
}
