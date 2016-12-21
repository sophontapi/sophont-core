<?php

namespace Sophont\Core;

/**
 * Interface UpdatedTsAwareEntityInterface
 * @package Sophont\Core
 */
interface UpdatedTsAwareEntityInterface
{
    public function setUpdatedAt(\DateTime $dateTime);

    public function getUpdatedAt();
}