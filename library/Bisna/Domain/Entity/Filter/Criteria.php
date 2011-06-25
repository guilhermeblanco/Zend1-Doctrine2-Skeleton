<?php

namespace Bisna\Domain\Entity\Filter;

use Doctrine\ORM\QueryBuilder;

/**
 * Composes the Doctrine QueryBuilder
 *
 * @category Bisna
 * @package Domain
 * @subpackage Filter
 */
class Criteria
{
    /**
     * @var Doctrine\ORM\QueryBuilder
     */
    protected $queryBuilder;
    
    /**
     * Constructor of entity filter criteria.
     * 
     * @param Doctrine\ORM\QueryBuilder $queryBuilder 
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
    
    /**
     * Retrieve the Doctrine query expression builder.
     * 
     * @return Doctrine\ORM\Query\Expr
     */
    public function expr()
    {
        return $this->queryBuilder->expr();
    }
    
    /**
     * Retrieve the Doctrine query associated to this filter criteria.
     * 
     * @return Doctrine\ORM\Query
     */
    public function getQuery()
    {
        return $this->queryBuilder->getQuery();
    }
    
    /**
     * Gets the root alias of the filter criteria.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *     
     *     $filter->getRootAlias(); // 'u'
     * </code>
     *
     * @return string
     */
    public function getRootAlias()
    {
        $rootAliases = $this->queryBuilder->getRootAliases();
        
        return $rootAliases[0];
    }
    
    /**
     * Gets the root entity of the filter criteria.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *     
     *     $filter->getRootEntity(); // 'User'
     * </code>
     *
     * @return string
     */
    public function getRootEntity()
    {
        $rootEntities = $this->queryBuilder->getRootEntities();
        
        return $rootEntities[0];
    }
    
    /**
     * Creates and adds a join over an entity association to the filter criteria.
     *
     * The entities in the joined association will be fetched as part of the query
     * result if the alias used for the joined association is placed in the select
     * expressions.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->leftJoin('u.Phonenumbers', 'p');
     * </code>
     * 
     * @param string $join Doctrine join (ie. "e.groups")
     * @param string $alias Doctrine join alias (ie. "g")
     * 
     * @return Criteria 
     */
    public function leftJoin($join, $alias)
    {
        $this->queryBuilder->addSelect($alias);
        $this->queryBuilder->leftJoin($join, $alias);
        
        return $this;
    }
    
    /**
     * Creates and adds a join over an entity association to the filter criteria.
     *
     * The entities in the joined association will be fetched as part of the query
     * result if the alias used for the joined association is placed in the select
     * expressions.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->innerJoin('u.Phonenumbers', 'p');
     * </code>
     * 
     * @param string $join Doctrine join (ie. "e.groups")
     * @param string $alias Doctrine join alias (ie. "g")
     * 
     * @return Criteria 
     */
    public function innerJoin($join, $alias)
    {
        $this->queryBuilder->addSelect($alias);
        $this->queryBuilder->innerJoin($join, $alias);
        
        return $this;
    }
    
    /**
     * Specifies one or more restrictions to the query result.
     * Replaces any previously specified restrictions, if any.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->where('u.id = ?');
     *
     *     // You can optionally programatically build and/or expressions
     *     $filter = $service->buildFilterCriteria('u')
     *     
     *     $or = $filter->expr()->orx();
     *     $or->add($filter->expr()->eq('u.id', 1));
     *     $or->add($filter->expr()->eq('u.id', 2));
     *
     *     $filter->where($or);
     * </code>
     *
     * @param mixed $predicates The restriction predicates.
     * 
     * @return Criteria
     */
    public function where($predicates)
    {
        call_user_func_array(array($this->queryBuilder, 'where'), func_get_args());
        
        return $this;
    }
    
    /**
     * Adds one or more restrictions to the query results, forming a logical
     * conjunction with any previously specified restrictions.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->where('u.username LIKE ?1')
     *         ->andWhere('u.is_active = 1');
     * </code>
     *
     * @see where()
     * @param mixed $where The query restrictions.
     * 
     * @return Criteria
     */
    public function andWhere($where)
    {
        call_user_func_array(array($this->queryBuilder, 'andWhere'), func_get_args());
        
        return $this;
    }
    
    /**
     * Adds one or more restrictions to the query results, forming a logical
     * conjunction with any previously specified restrictions.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->where('u.username LIKE ?1')
     *         ->orWhere('u.is_active = 1');
     * </code>
     *
     * @see where()
     * @param mixed $where The query restrictions.
     * 
     * @return Criteria
     */
    public function orWhere($where)
    {
        call_user_func_array(array($this->queryBuilder, 'orWhere'), func_get_args());
        
        return $this;
    }

    /**
     * Specifies an ordering for the query results.
     * Replaces any previously specified orderings, if any.
     *
     * @param string $sort The ordering expression.
     * @param string $order The ordering direction.
     * 
     * @return Criteria
     */
    public function orderBy($sort, $order = null)
    {
        $this->queryBuilder->orderBy($sort, $order);
        
        return $this;
    }

    /**
     * Adds an ordering to the query results.
     *
     * @param string $sort The ordering expression.
     * @param string $order The ordering direction.
     * 
     * @return Criteria
     */
    public function addOrderBy($sort, $order = null)
    {
        $this->queryBuilder->addOrderBy($sort, $order);
        
        return $this;
    }
    
    /**
     * Sets a filter criteria parameter for the query being constructed.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->where('u.id = :user_id')
     *         ->setParameter(':user_id', 1);
     * </code>
     *
     * @param string|integer $key The parameter position or name.
     * @param mixed $value The parameter value.
     * @param string|null $type PDO::PARAM_* or \Doctrine\DBAL\Types\Type::* constant
     * 
     * @return Criteria 
     */
    public function setParameter($key, $value, $type = null)
    {
        $this->queryBuilder->setParameter($key, $value, $type);
        
        return $this;
    }
    
    /**
     * Sets a collection of filter criteria parameters for the query being constructed.
     *
     * <code>
     *     $filter = $service->buildFilterCriteria('u')
     *         ->where('u.id = :user_id1 OR u.id = :user_id2')
     *         ->setParameters(array(
     *             ':user_id1' => 1,
     *             ':user_id2' => 2
     *         ));
     * </code>
     *
     * @param array $params The filter criteria parameters to set.
     * @param array $types array of PDO::PARAM_* or \Doctrine\DBAL\Types\Type::* constants
     * 
     * @return Criteria
     */
    public function setParameters(array $params, array $types = array())
    {
        $this->queryBuilder->setParameters($params, $types);
        
        return $this;
    }

    /**
     * Gets all defined query parameters for the query being constructed.
     *
     * @return array The currently defined query parameters.
     */
    public function getParameters()
    {
        return $this->queryBuilder->getParameters();
    }

    /**
     * Gets a (previously set) query parameter of the query being constructed.
     * 
     * @param mixed $key The key (index or name) of the bound parameter.
     * 
     * @return mixed The value of the bound parameter.
     */
    public function getParameter($key)
    {
        return $this->queryBuilder->getParameter($key);
    }

    /**
     * Sets the position of the first result to retrieve (the "offset").
     *
     * @param integer $firstResult The first result to return.
     * 
     * @return Criteria
     */
    public function setOffset($offset)
    {
        $this->queryBuilder->setFirstResult($offset);
        
        return $this;
    }

    /**
     * Gets the position of the first result the query object was set to retrieve (the "offset").
     * Returns NULL if {@link setFirstResult} was not applied to this QueryBuilder.
     * 
     * @return integer The position of the first result.
     */
    public function getOffset()
    {
        return $this->queryBuilder->getFirstResult();
    }
    
    /**
     * Sets the maximum number of results to retrieve (the "limit").
     * 
     * @param integer $maxResults The maximum number of results to retrieve.
     * 
     * @return Criteria
     */
    public function setMaxResults($maxResults)
    {
        $this->queryBuilder->setMaxResults($maxResults);
        
        return $this;
    }
    
    /**
     * Gets the maximum number of results the query object was set to retrieve (the "limit").
     * Returns NULL if {@link setMaxResults} was not applied to this query builder.
     * 
     * @return integer Maximum number of results.
     */
    public function getMaxResults()
    {
        return $this->queryBuilder->getMaxResults();
    }
}