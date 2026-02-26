<?php
#Modelo de base que herendan los genericos

namespace Paw\Core;

use Paw\Core\Database\QueryBuilder;
use Paw\Core\Traits\Loggable;

class Model
{
    use Loggable;

    public $queryBuilder;

    public function __construct(QueryBuilder $qb)
    {
        $this->queryBuilder = $qb;
    }

    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->queryBuilder = $qb;
    }

}