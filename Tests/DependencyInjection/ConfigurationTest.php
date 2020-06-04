<?php

namespace Itamit\ClassifierErrorBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Bundle configuration test.
 */
class ConfigurationTest extends TestCase
{
    /**
     * Bundle configuration.
     *
     * @var Configuration
     */
    private $configuration;

    /**
     * Sets the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
    }

    /**
     * Cleans the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->configuration = null;
    }

    /**
     * Checks bundle configuration return.
     */
    public function testTreeBuilder()
    {
        $builder = $this->configuration->getConfigTreeBuilder();

        $this->assertInstanceOf(TreeBuilder::class, $builder);
        $this->assertEquals(Configuration::NAME_BUNDLE_CONFIGURATION, $builder->buildTree()->getName());
    }
}
