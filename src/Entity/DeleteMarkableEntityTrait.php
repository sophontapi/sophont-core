<?php

namespace Sophont\Core;

use Sophont\Core\Entity\AbstractEntity;

/**
 * Class DeleteMarkableEntityTrait
 * @package Application\Mapper
 */
Trait DeleteMarkableEntityTrait
{
    /**
     * @var int
     */
    protected $deletedValue = AbstractEntity::STATUS_DELETED;

    /**
     * @var int
     */
    protected $availableValue = AbstractEntity::STATUS_ACTIVE;

    /**
     * @var string
     */
    protected $fieldName = 'status';

    /**
     * Set deleted flag
     *
     * @return mixed
     */
    public function markEntityAsDeleted()
    {
        $setter = 'set' . ucfirst($this->fieldName());
        if (method_exists($this, $setter)) {
            return call_user_func_array([$this, $setter], [$this->deletedValue()]);
        }

        return $this->$setter = $this->deletedValue();
    }

    /**
     * Restore entity from "deleted"
     *
     * @return mixed
     */
    public function unmarkEntityAsDeleted()
    {
        $fieldName = $this->fieldName();
        $setterName = 'set' . ucfirst($fieldName);
        if (method_exists($this, $setterName)) {
            return call_user_func_array([$this, $setterName], [$this->availableValue()]);
        }

        return $this->$fieldName = $this->availableValue();
    }

    /**
     * Check if entity is deleted
     *
     * @return mixed
     */
    public function isDeleted()
    {
        $fieldName = $this->fieldName();
        $getterName = 'get' . ucfirst($fieldName);
        if (method_exists($this, $getterName)) {
            $value = call_user_func([$this, $getterName]);
        } else {
            $value = $this->$fieldName;
        }

        return $value === $this->deletedValue();
    }

    /**
     * Get "deleted" marker field name
     *
     * @return mixed
     */
    public function fieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return int
     */
    public function deletedValue()
    {
        return $this->deletedValue;
    }

    /**
     * @return int
     */
    public function availableValue()
    {
        return $this->availableValue;
    }
}