<?php

namespace Itamit\ClassifierErrorBundle\Factory;

use Itamit\ClassifierErrorBundle\Dto\Error;
use Itamit\ClassifierErrorBundle\Dto\ErrorInterface;
use Itamit\ClassifierErrorBundle\Factory\Exception\ErrorDataParameterNotFoundException;

/**
 * Factory DTO Error Messages.
 */
class ErrorDtoFactory implements ErrorDtoFactoryInterface
{
    const MSG_ERROR_NOT_HTTP_CODE = 'The error data array does not contain HTTP code';
    const MSG_ERROR_NOT_MESSAGE = 'The error data array does not contain an error message';

    /**
     * Generates DTO error messages.
     *
     * @param integer $code
     * @param array   $data
     *
     * @return ErrorInterface
     *
     * @throws ErrorDataParameterNotFoundException
     */
    public function createDto(int $code, array $data): ErrorInterface
    {
        if (!(array_key_exists(self::MESSAGE_FIELD, $data) && !empty($data[self::MESSAGE_FIELD]))) {
            $message = sprintf(
                self::MSG_ERROR_NOT_MESSAGE.' (%s)',
                self::MESSAGE_FIELD
            );

            throw new ErrorDataParameterNotFoundException($message);
        }

        if (!(array_key_exists(self::HTTP_CODE_FIELD, $data) && !empty($data[self::HTTP_CODE_FIELD]))) {
            $message = sprintf(
                self::MSG_ERROR_NOT_HTTP_CODE.' (%s)',
                self::HTTP_CODE_FIELD
            );

            throw new ErrorDataParameterNotFoundException($message);
        }

        return new Error($code, $data[self::MESSAGE_FIELD], $data[self::HTTP_CODE_FIELD]);
    }
}
