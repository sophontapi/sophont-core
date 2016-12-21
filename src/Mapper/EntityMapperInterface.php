<?php

namespace Sophont\Core\Mapper;

use Sophont\Core\Entity\AbstractEntity;

/**
 * Interface EntityMapperInterface
 * @package Sophont\Core
 */
interface EntityMapperInterface
{
    /**
     * Find an entity by its id
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Delete entity from db. If entity is DeleteMarkable entity it should only mark it as deleted
     * in database
     *
     * @param AbstractEntity $entity
     * @return mixed
     */
    public function delete(AbstractEntity $entity);

    /**
     * Update entity with new one
     *
     * @param AbstractEntity $entity
     * @return mixed
     */
    public function update(AbstractEntity $entity);
}