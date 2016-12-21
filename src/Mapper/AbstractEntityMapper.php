<?php

namespace Sophont\Core\Mapper;

use Doctrine\ORM\EntityManager;

use Sophont\Core\CreateTsAwareEntityInterface;
use Sophont\Core\DeleteMarkableEntityInterface;
use Sophont\Core\Entity\AbstractEntity;
use Sophont\Core\Exception\MappingInvalidIdentificatorException;
use Sophont\User\Entity\UserAccount;

use Sophont\User\Mapper\UserAccountAwareEntityInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class AbstractEntityMapper
 * @package Sophont\Core
 */
abstract class AbstractEntityMapper implements EntityMapperInterface
{
    /** @var EntityManager $entityManager */
    protected $entityManager;

    /** @var AuthenticationService $authService */
    protected $authService;

    /**
     * AbstractEntityMapper constructor.
     *
     * @param EntityManager $entityManager
     * @param AuthenticationService $authService
     */
    public function __construct(EntityManager $entityManager, AuthenticationService $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    /**
     * @param $id
     * @return AbstractEntity
     * @throws MappingInvalidIdentificatorException
     */
    public function find($id)
    {
        $id = (int)$id;
        if (!$id) {
            throw new MappingInvalidIdentificatorException('Invalid ID provided');
        }

        $entity = $this->getEntityManager()
            ->getRepository($this->getEntityRepository())
            ->find($id);

        if ($entity instanceof DeleteMarkableEntityInterface && $entity->isDeleted()) {
            return null;
        }

        return $entity;
    }

    /**
     * @param $entity
     *
     * @return boolean
     */
    public function delete(AbstractEntity $entity)
    {
        $entityManager = $this->getEntityManager();

        if ($entity instanceof DeleteMarkableEntityInterface) {
            $this->markEntityAsDeleted($entity);
            $this->update($entity);
        } else {
            $entityManager->remove($entity);
        }
        $entityManager->flush();
        return true;
    }

    /**
     * @param $entity
     */
    public function update(AbstractEntity $entity)
    {
        $entityManager = $this->getEntityManager();
        if (
            $entity instanceof UserAccountAwareEntityInterface &&
            $this->getAuthenticationService()->hasIdentity() &&
            null === $entity->getUser()
        ) {
            /** @var UserAccount $identity */
            $identity = $this->getAuthenticationService()->getIdentity();
            $identity = $this->getEntityManager()->merge($identity);
            $entity->setUser($identity);
        }
        $entityManager->merge($entity);
        $entityManager->flush();
    }

    /**
     * @param $id
     *
     * @return null
     * @throws MappingInvalidIdentificatorException
     */
    public function deleteById($id)
    {
        $id = (int)$id;
        if (!$id) {
            throw new MappingInvalidIdentificatorException('Invalid ID provided');
        }

        $entity = $this->find($id);
        if (null === $entity) {
            return null;
        }
        return $this->delete($entity);
    }

    /**
     * @param $entity
     * @return object
     */
    public function persist($entity)
    {
        if (is_array($entity)) {
            $className = $this->getEntityRepository();
            $entity = $this->hydrator->hydrate($entity, new $className);
        }

        if ($entity instanceof CreateTsAwareEntityInterface) {
            $entity->setCreateTs(new \DateTime());
        }

        if (
            $entity instanceof UserAwareEntityInterface &&
            $this->getAuthService()->hasIdentity() &&
            null === $entity->getUser()
        ) {
            /** @var UserAccount $identity */
            $identity = $this->getAuthService()->getIdentity();
            $identity = $this->getEntityManager()->merge($identity);
            $entity->setUser($identity);
        }

        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    /**
     * Find entities by search criteria
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entity = $this->getEntityRepository();
        $simpleInstance = new $entity();
        if ($simpleInstance instanceof DeleteMarkableEntityInterface) {
            $criteria[$simpleInstance->fieldName()] = 0;
        }

        return $this->getEntityManager()->getRepository(
            $this->getEntityRepository()
        )->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Find an entity by search criteria
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @return array
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $entity = $this->getEntityRepository();
        $simpleInstance = new $entity();
        if ($simpleInstance instanceof DeleteMarkableEntityInterface) {
            $criteria[$simpleInstance->fieldName()] = $simpleInstance->availableValue();
        }

        return $this->getEntityManager()->getRepository(
            $this->getEntityRepository()
        )->findOneBy($criteria, $orderBy);
    }

    /**
     * @param DeleteMarkableEntityInterface $entity
     * @return bool
     */
    protected function markEntityAsDeleted(DeleteMarkableEntityInterface $entity)
    {
        $entity->markEntityAsDeleted();
        $this->getEntityManager()->merge($entity);

        return true;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Returns entity repository for ORM\EntityManager
     *
     * @return string
     */
    public abstract function getEntityRepository();
}