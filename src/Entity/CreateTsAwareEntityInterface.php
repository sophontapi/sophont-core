<?php

namespace Sophont\Core;

/**
 * Class CreateTsAwareEntityInterface
 * @package Sophont\Core
 */
interface CreateTsAwareEntityInterface
{
    /**
     * Set entity created date.
     *
     * @param \DateTime $createTs
     * @return mixed
     */
    public function setCreateTs(\DateTime $createTs);

    /**
     * Get entity created date
     *
     * @return \DateTime
     */
    public function getCreateTs();
}