<?php

namespace Sophont\Core\Entity;

/**
 * Class AbstractEntity
 * @package Sophont\Core
 */
abstract class AbstractEntity
{
    // Entity 'active' status. This status means that entity is available to use
    const STATUS_ACTIVE = 0;

    // Entity 'delete' status. This status means that entity has been removed and
    // should not be concerned or interacted in you application / statistics
    const STATUS_DELETED = 1;

    // Entity 'deactive' status. This status means that entity has been disabled for some
    // reason, but it is available for interaction. E.g. if it is news or article it may be in
    // editing state, or a user who has been banned
    const STATUS_DEACTIVE = 2;

    const DEFAULT_STATUS = self::STATUS_ACTIVE;

    // Create new entity
    abstract function getInstance();
}