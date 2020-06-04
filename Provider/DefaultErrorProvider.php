<?php

namespace Itamit\ClassifierErrorBundle\Provider;

/**
 * Default error provider.
 */
class DefaultErrorProvider implements ErrorProviderInterface
{
    const FORM_VALIDATION_ERROR = 0xE0001;

    /**
     * Returns a list of supported errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return [
            self::FORM_VALIDATION_ERROR => [
                self::HTTP_CODE_FIELD => 400,
                self::MESSAGE_FIELD   => 'Form validation error',
            ],
        ];
    }
}
