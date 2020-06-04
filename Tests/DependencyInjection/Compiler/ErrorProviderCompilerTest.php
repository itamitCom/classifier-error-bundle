<?php

namespace Itamit\ClassifierErrorBundle\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Error provider compiler tests.
 */
class ErrorProviderCompilerTest extends TestCase
{
    /**
     * Container.
     *
     * @var MockObject|ContainerBuilder
     */
    private $container;

    /**
     * Service definition.
     *
     * @var MockObject|Definition
     */
    private $definition;

    /**
     * Compiler.
     *
     * @var ErrorProviderCompiler
     */
    private $compiler;

    /**
     * Name of the error classifier service.
     *
     * @var string
     */
    private $service = ErrorProviderCompiler::NAME_CLASSIFIER_ERROR_SERVICE;

    /**
     * Name tag.
     *
     * @var string
     */
    private $tag = ErrorProviderCompiler::NAME_ERROR_PROVIDER_TAG;

    /**
     * Sets the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->container  = $this->getMockBuilder(ContainerBuilder::class)->getMock();
        $this->definition = $this->getMockBuilder(Definition::class)->getMock();
        $this->compiler   = new ErrorProviderCompiler();
    }

    /**
     * Cleans the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->container  = null;
        $this->definition = null;
        $this->compiler   = null;
    }

    /**
     * Tests adding providers.
     */
    public function testRegisterProvider()
    {
        $services = [
            'some_service' => [
                'some_value',
                'another_value',
            ],
            'another_service' => [
                'some_value',
            ],
            'empty_service' => []
        ];

        $tags = [
            'some_service',
            'another_service',
            'empty_service',
        ];

        for ($i = 0, $count = count($tags); $i < $count; $i++) {
            $this->definition->expects($this->at($i))->method('addMethodCall')->with(
                $this->equalTo('addProvider'),
                $this->callback(function (array $references) use ($tags, $i) {
                    $this->assertInstanceOf(Reference::class, $references[0]);
                    $this->assertEquals($tags[$i], (string) $references[0]);

                    return true;
                })
            );
        }

        $this->container
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo($this->service))
            ->willReturn(true);

        $this->container
            ->expects($this->once())
            ->method('findDefinition')
            ->with($this->equalTo($this->service))
            ->willReturn($this->definition);

        $this->container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo($this->tag))
            ->willReturn($services);

        $this->compiler->process($this->container);
    }

    /**
     * Tests pass registration of providers if the classifier service is not specified.
     */
    public function testRegisterProviderForNotExistsFactory()
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo($this->service))
            ->willReturn(false);

        $this->container->expects($this->never())->method('findDefinition');
        $this->container->expects($this->never())->method('findTaggedServiceIds');
        $this->compiler->process($this->container);
    }
}