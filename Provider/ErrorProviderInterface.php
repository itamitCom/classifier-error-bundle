<?php

namespace Itamit\ClassifierErrorBundle\Provider;

/**
 * Error provider interface.
 */
interface ErrorProviderInterface
{
    /**
     * Field name storing HTTP error code.
     *
     * @const string
     */
    const HTTP_CODE_FIELD = 'httpCode';

    /**
     * The name of the field storing the test description of the error.
     *
     * @const string
     */
    const MESSAGE_FIELD = 'message';

    /**
     * Returns a list of supported errors.
     *
     * @return array
     */
    public function getErrors(): array;
}
