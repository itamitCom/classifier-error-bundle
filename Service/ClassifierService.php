<?php

namespace Itamit\ClassifierErrorBundle\Service;

use Itamit\ClassifierErrorBundle\Dto\ErrorInterface;
use Itamit\ClassifierErrorBundle\Factory\ErrorDtoFactoryInterface;
use Itamit\ClassifierErrorBundle\Provider\ErrorProviderInterface;

/**
 * Error code classifier service.
 */
class ClassifierService implements ClassifierInterface
{
    /**
     * Full array of errors.
     *
     * @var array
     */
    private $errors = [];

    /**
     * DTO Error Factory.
     *
     * @var ErrorDtoFactoryInterface
     */
    private $errorDtoFactory;

    /**
     * Classifier service constructor.
     *
     * @param ErrorDtoFactoryInterface $errorDtoFactory
     */
    public function __construct(ErrorDtoFactoryInterface $errorDtoFactory)
    {
        $this->errorDtoFactory = $errorDtoFactory;
    }

    /**
     * Returns error information by code
     *
     * @param integer $errorCode
     *
     * @return ErrorInterface
     *
     * @throws Exception\EntryNotFoundException
     */
    public function get(int $errorCode): ErrorInterface
    {
        if (!$this->has($errorCode)) {
            $message = sprintf('Code %s not found in error registry', $errorCode);

            throw new Exception\EntryNotFoundException($message);
        }

        return $this->errorDtoFactory->createDto($errorCode, $this->errors[$errorCode]);
    }

    /**
     * Checks for code in the classifier.
     *
     * @param integer $errorCode
     *
     * @return boolean
     */
    public function has(int $errorCode): bool
    {
        return array_key_exists($errorCode, $this->errors);
    }

    /**
     * Returns the entire array of errors.
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->errors;
    }

    /**
     * Adds error provider in DI.
     *
     * @param ErrorProviderInterface $provider
     *
     * @throws Exception\IntersectedKeysFoundException
     */
    public function addProvider(ErrorProviderInterface $provider)
    {
        $providerErrors = $provider->getErrors();
        $intersectKeys  = array_intersect_key($this->errors, $providerErrors);

        if (!empty($intersectKeys)) {
            $message = sprintf(
                'The intersecting keys %s were found in provider %s',
                implode(', ', array_keys($intersectKeys)),
                get_class($provider)
            );

            throw new Exception\IntersectedKeysFoundException($message);
        }

        $this->errors += $providerErrors;
    }
}
