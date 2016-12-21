<?php

namespace Sophont\Core\Service;

/**
 * Class AbstractService
 */
abstract class AbstractService
{
    /** @var bool $hasError */
    protected $hasError;

    /** @var array $errors */
    protected $errors = [];

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->hasError;
    }

}