<?php
namespace App\Trait;

use Doctrine\ORM\Query;

trait PagineableRepositoryTrait
{
    /**
     * Recupère la query pour la pagination
     * @return Query
     */
    public function getPaginationQuery() : Query
    {
        return $this->createQueryBuilder('c')->getQuery();
    }
}