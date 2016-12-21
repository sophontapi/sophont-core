<?php

namespace Sophont\Core;

/**
 * Class DeleteMarkableEntityInterface
 * @package Sophont\Core
 */
interface DeleteMarkableEntityInterface
{
    /**
     * Set entity deleted flag
     *
     * @return mixed
     */
    public function markEntityAsDeleted();

    /**
     * Restore entity from "deleted"
     *
     * @return mixed
     */
    public function unmarkEntityAsDeleted();

    /**
     * Check if entity is deleted
     *
     * @return mixed
     */
    public function isDeleted();

    /**
     * Get "deleted" marker field name
     *
     * @return mixed
     */
    public function fieldName();

    /**
     *
     * @return mixed
     */
    public function deletedValue();

    /**
     *
     * @return mixed
     */
    public function availableValue();
}