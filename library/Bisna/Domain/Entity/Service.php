<?php

namespace Bisna\Domain;

use Bisna\Service\InternalService;

/**
 * Entity Service, holds the minimum contract for every Service in platform.
 *
 * @category Bisna
 * @package Domain
 * @subpackage Entity
 */
class Service extends InternalService
{
    /**
     * Create a new entity filter criteria.
     * 
     * @param string $alias Optional root alias (default = "e")
     * 
     * @return Filter\Criteria 
     */
    public function buildFilterCriteria($alias = 'e')
    {
        return new Filter\Criteria(
            $this->getRepository($this->options['r'])->createQueryBuilder($alias)
        );
    }
    
    /**
     * Returns a list of filtered entities.
     *
     * @param Filter\Criteria $criteria
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function filter(Filter\Criteria $criteria = null)
    {
        try {
            if ($criteria === null) {
                $criteria = $this->buildFilterCriteria();
            }
            
            return $this->getRepository($this->options['r'])->filter($criteria);
        } catch (\Exception $e) {
            $this->dispatchExceptionEvent($e);
            
            throw new BaseService\Exception('Unable to retrieve entities.', 500, $e);
        }
    }

    /**
     * Retrieve the object by its identifier.
     *
     * @param integer|string $id
     * 
     * @return mixed
     */
    public function get($id)
    {
        try {
            return $this->getRepository($this->options['r'])->find($id);
        } catch (\Exception $e) {
            $this->dispatchExceptionEvent($e);
            
            throw new BaseService\Exception('Unable to retrieve with ID: ' . $id, 500, $e);
        }
    }

    /**
     * Delete an entity by its identifier.
     *
     * @param integer|string $id
     * 
     * @return boolean
     */
    public function delete($id)
    {
        $em = $this->getEntityManager($this->options['rw']);
        
        try {
            $em->beginTransaction();
            
            $this->getRepository($this->options['rw'])->delete($id);
            
            $em->flush();
            $em->commit();
            
            return true;
        } catch (\Exception $e) {
            $em->rollback();
            
            $this->dispatchExceptionEvent($e);
            
            throw new BaseService\Exception('Unable to delete entity with ID: ' . $id, 500, $e);
        }
    }
    
    /**
     * Save the entity on storage.
     *
     * @param mixed $entity
     * 
     * @return boolean
     */
    public function save($entity)
    {
        $em = $this->getEntityManager($this->options['rw']);
        
        try {
            $em->beginTransaction();
            
            $this->getRepository($this->options['rw'])->save($entity);
            
            $em->flush();
            $em->commit();

            return true;
        } catch (\Exception $e) {
            $em->rollback();
            
            $this->dispatchExceptionEvent($e);
           
            $errorMessage = ($entity->getId() === null)
                ? 'Unable to save new entity.' 
                : 'Unable to save entity with ID: ' . $entity->getId();
            
            throw new BaseService\Exception($errorMessage, 500, $e);
        }
    }
    
    /**
     * Retrieve the associated repository to this Service's entity.
     * 
     * @param string $emName
     * 
     * @return Bisna\Domain\Entity\Repository 
     */
    protected function getRepository($emName = null)
    {
        return $this->getEntityManager($emName)->getRepository($this->options['entityClassName']);
    }
    
    /**
     * Dispatches an exception event through EventManager Sservice.
     *
     * @param \Exception $e 
     * 
     * @return void
     */
    protected function dispatchExceptionEvent(\Exception $e)
    {
        if ($this->getServiceLocator()->hasInternalService('Notification')) {
            $eventManager = $this->getServiceLocator()->getInternalService('Notification');
            $eventManager->dispatch('exception', $e);
        }
    }
}