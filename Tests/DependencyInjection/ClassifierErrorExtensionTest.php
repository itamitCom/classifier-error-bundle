<?php

namespace Itamit\ClassifierErrorBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
* Tests load bundle.
*/
class ClassifierErrorExtensionTest extends TestCase
{
    /**
     * Container.
     *
     * @var MockObject|ContainerBuilder
     */
    private $container;

    /**
     * Loader.
     *
     * @var ClassifierErrorExtension
     */
    private $extension;

    /**
     * Required Services
     *
     * @var array
     */
    private $services = [
        'classifier_error.error_dto_factory',
        'classifier_error.classifier_service',
        'classifier_error.default_error_provider',
        'classifier_error.exception_listener',
    ];

    /**
     * Sets the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->container = $this->getMockBuilder(ContainerBuilder::class)->getMock();
        $this->extension = new ClassifierErrorExtension();
    }

    /**
     * Cleans the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->container = null;
        $this->extension = null;
    }

    /**
     * Checks configuration initialization.
     */
    public function testLoad()
    {
        $services         = [];
        $servicesCallback = $this->callback(function ($service) use (&$services) {
            $services[] = $service;
            return true;
        });

        $this->container->expects($this->any())->method('setDefinition')->with($servicesCallback);
        $this->extension->load([], $this->container);

        foreach ($this->services as $service) {
            $this->assertContains($service, $services);
        }

        $undeclaredServices   = array_diff($services, $this->services);
        $undeclaredMessage    = 'The container contains undeclared services %s: %s';

        $this->assertEmpty(
            $undeclaredServices,
            sprintf($undeclaredMessage, 'services', implode(',', $undeclaredServices))
        );
    }
}
