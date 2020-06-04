<?php

namespace Itamit\ClassifierErrorBundle\Dto;

/**
 * Error Information Interface.
 */
interface ErrorInterface
{
    /**
     * Returns classifier Error Code.
     *
     * @return integer
     */
    public function getErrorCode(): int;

    /**
     * Returns an error code in Hex format.
     *
     * @return string
     */
    public function getHexCode(): string;

    /**
     * Returns error text description.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Returns HTTP code corresponding error.
     *
     * @return integer
     */
    public function getHttpCode(): int;
}
