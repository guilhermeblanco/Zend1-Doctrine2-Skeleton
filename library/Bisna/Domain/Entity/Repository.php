<?php

namespace Bisna\Domain\Entity;

use Doctrine\ORM\EntityRepository,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * Wraps the Doctrine EntityRepository
 *
 * @category Bisna
 * @package Domain
 * @subpackage Entity
 */
abstract class Repository extends EntityRepository
{
    /**
     * Persists an Entity
     *
     * @param mixed $object
     */
    public function save($object)
    {
        $this->getEntityManager()->persist($object);
    }

    /**
     * Deletes an entity
     * 
     * @param integer $id 
     */
    public function delete($id)
    {
        $em = $this->getEntityManager();
        
        //Load Reference (no DB operation)
        $reference = $em->getReference($this->getEntityName(), $id);

        //Remove based on entity
        $em->remove($reference);
    }

    /**
     * Returns a filtered list of entities according to a given criteria
     *
     * @param Filter\Criteria $criteria
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function filter(Filter\Criteria $criteria)
    {
        $query  = $criteria->getQuery();
        $result = $query->getResult();
        
        return new ArrayCollection($result);
    }
}