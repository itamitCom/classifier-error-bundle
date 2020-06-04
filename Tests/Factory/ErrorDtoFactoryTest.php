<?php

namespace Itamit\ClassifierErrorBundle\Factory;

use Itamit\ClassifierErrorBundle\Dto\ErrorInterface;
use Itamit\ClassifierErrorBundle\Factory\Exception\ErrorDataParameterNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Factory test DTO errors.
 */
class ErrorDtoFactoryTest extends TestCase
{
    /**
     * Factory DTO Error Messages.
     *
     * @var ErrorDtoFactoryInterface
     */
    private $errorFactory;

    /**
     * Sets the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->errorFactory = new ErrorDtoFactory();
    }

    /**
     * Cleans the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->errorFactory = null;
    }

    /**
     * Tests the creation of DTO.
     */
    public function testCreateDto()
    {
        $data    = [
            ErrorDtoFactoryInterface::MESSAGE_FIELD   => 'Some message error',
            ErrorDtoFactoryInterface::HTTP_CODE_FIELD => 400,
        ];
        $dto     = $this->errorFactory->createDto(100, $data);

        $this->assertInstanceOf(ErrorInterface::class, $dto);
        $this->assertEquals(100, $dto->getErrorCode());
        $this->assertEquals('Some message error', $dto->getMessage());
        $this->assertEquals(400, $dto->getHttpCode());
    }

    /**
     * Tests error when creating with missing HTTP code.
     */
    public function testCreateDtoHttpCodeNotFound()
    {
        $data = [
            ErrorDtoFactoryInterface::MESSAGE_FIELD => 'Some message error',
        ];

        $this->expectException(ErrorDataParameterNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                ErrorDtoFactory::MSG_ERROR_NOT_HTTP_CODE.' (%s)',
                ErrorDtoFactoryInterface::HTTP_CODE_FIELD
            )
        );

        $this->errorFactory->createDto(100, $data);
    }

    /**
     * Tests error when creating with missing message.
     */
    public function testCreateDtoMessageNotFound()
    {
        $data = [
            ErrorDtoFactoryInterface::HTTP_CODE_FIELD => 400,
        ];

        $this->expectException(ErrorDataParameterNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                ErrorDtoFactory::MSG_ERROR_NOT_MESSAGE.' (%s)',
                ErrorDtoFactoryInterface::MESSAGE_FIELD
            )
        );

        $this->errorFactory->createDto(100, $data);
    }
}
