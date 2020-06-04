<?php

namespace Itamit\ClassifierErrorBundle\Dto;

/**
 * Error Information.
 */
class Error implements ErrorInterface
{
    /**
     * Classifier Error Code.
     *
     * @var integer
     */
    private $errorCode;

    /**
     * Error text description.
     *
     * @var string
     */
    private $errorMessage;

    /**
     * HTTP code corresponding error.
     *
     * @var integer
     */
    private $errorHttpCode;

    /**
     * Error constructor
     *
     * @param integer $errorCode
     * @param string  $errorMessage
     * @param integer $errorHttpCode
     */
    public function __construct(int $errorCode, string $errorMessage, int $errorHttpCode)
    {
        $this->errorCode     = $errorCode;
        $this->errorMessage  = $errorMessage;
        $this->errorHttpCode = $errorHttpCode;
    }

    /**
     * Returns classifier Error Code.
     *
     * @return integer
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Returns an error code in Hex format.
     *
     * @return string
     */
    public function getHexCode(): string
    {
        return '0x'.strtoupper(dechex($this->getErrorCode()));
    }

    /**
     * Returns error text description.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Returns HTTP code corresponding error.
     *
     * @return integer
     */
    public function getHttpCode(): int
    {
        return $this->errorHttpCode;
    }
}
