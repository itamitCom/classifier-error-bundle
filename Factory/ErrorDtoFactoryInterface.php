<?php

namespace Itamit\ClassifierErrorBundle\Factory;

use Itamit\ClassifierErrorBundle\Dto\ErrorInterface;
use Itamit\ClassifierErrorBundle\Provider\ErrorProviderInterface;

/**
 * DTO factory interface error messages.
 */
interface ErrorDtoFactoryInterface
{
    const MESSAGE_FIELD   = ErrorProviderInterface::MESSAGE_FIELD;
    const HTTP_CODE_FIELD = ErrorProviderInterface::HTTP_CODE_FIELD;

    /**
     * Generates DTO error messages.
     *
     * @param integer $code
     * @param array   $data
     *
     * @return ErrorInterface
     */
    public function createDto(int $code, array $data): ErrorInterface;
}
