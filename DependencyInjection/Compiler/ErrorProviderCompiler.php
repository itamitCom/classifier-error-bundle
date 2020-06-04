<?php

namespace Itamit\ClassifierErrorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Error provider compiler.
 */
class ErrorProviderCompiler implements CompilerPassInterface
{
    const NAME_CLASSIFIER_ERROR_SERVICE = 'classifier_error.classifier_service';
    const NAME_ERROR_PROVIDER_TAG = 'classifier_error.error_provider';

    /**
     * Init container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::NAME_CLASSIFIER_ERROR_SERVICE)) {
            return;
        }

        $definition = $container->findDefinition(self::NAME_CLASSIFIER_ERROR_SERVICE);
        $providers  = $container->findTaggedServiceIds(self::NAME_ERROR_PROVIDER_TAG);

        foreach (array_keys($providers) as $currentProvider) {
            $definition->addMethodCall('addProvider', [new Reference($currentProvider)]);
        }
    }
}