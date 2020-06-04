<?php

namespace Itamit\ClassifierErrorBundle\Provider;

use PHPUnit\Framework\TestCase;

/**
 * Common error provider test
 */
class DefaultErrorProviderTest extends TestCase
{
    /**
     * Default error provider.
     *
     * @var ErrorProviderInterface
     */
    private $errorProvider;

    /**
     * Sets the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->errorProvider = new DefaultErrorProvider();
    }

    /**
     * Cleans the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->errorProvider = null;
    }

    /**
     * Tests getting a list of errors.
     */
    public function testGetErrors()
    {
        $errors = $this->errorProvider->getErrors();

        $this->assertTrue(is_array($errors));
    }
}
