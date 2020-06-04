<?php

namespace Itamit\ClassifierErrorBundle\Service;

use Itamit\ClassifierErrorBundle\Dto\ErrorInterface;

/**
 * Error code classifier interface.
 */
interface ClassifierInterface
{
    /**
     * Returns error information by code.
     *
     * @param int $errorCode
     *
     * @return ErrorInterface
     */
    public function get(int $errorCode): ErrorInterface;

    /**
     * Checks for code in the classifier.
     *
     * @param int $errorCode
     *
     * @return bool
     */
    public function has(int $errorCode): bool;

    /**
     * Returns the entire array of errors.
     *
     * @return array
     */
    public function getAll(): array;
}
