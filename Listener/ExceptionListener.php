<?php

namespace Itamit\ClassifierErrorBundle\Listener;

use Itamit\ClassifierErrorBundle\Service\ClassifierInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Catches exceptions and returns an HTTP response if the error is classifiable.
 */
class ExceptionListener
{
    /**
     * Error Code Classifier.
     *
     * @var ClassifierInterface
     */
    protected $classifier;

    /**
     * ExceptionListener constructor.
     *
     * @param ClassifierInterface $classifier
     */
    public function __construct(ClassifierInterface $classifier)
    {
        $this->classifier  = $classifier;
    }

    /**
     * Listens for events caused by uncaught exceptions.
     *
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $exceptionCode = $exception->getCode();
        if (!$this->classifier->has($exceptionCode)) {
            return;
        }

        $view = $this->transform($exception);

        $responseCode = $this->classifier->get($exceptionCode)->getHttpCode();
        $response     = new JsonResponse($view, $responseCode);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    /**
     * Returns a transformed representation of a given dataset
     *
     * @param mixed $data
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function transform($data)
    {
        if (!$data instanceof \Exception) {
            return null;
        }

        if (!$this->classifier->has($data->getCode())) {
            throw $data;
        }

        $errorInfo = $this->classifier->get($data->getCode());

        return [
            'error' => [
                'code'    => $errorInfo->getHexCode(),
                'message' => $errorInfo->getMessage(),
                'fields'  => [],
            ]
        ];
    }
}
