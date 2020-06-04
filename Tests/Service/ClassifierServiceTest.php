<?php

namespace Itamit\ClassifierErrorBundle\Service;

use Itamit\ClassifierErrorBundle\Dto\ErrorInterface;
use Itamit\ClassifierErrorBundle\Factory\ErrorDtoFactoryInterface;
use Itamit\ClassifierErrorBundle\Provider\ErrorProviderInterface;
use Itamit\ClassifierErrorBundle\Service\Exception\EntryNotFoundException;
use Itamit\ClassifierErrorBundle\Service\Exception\IntersectedKeysFoundException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Error classifier service tests.
 */
class ClassifierServiceTest extends TestCase
{
    /**
     * Factory DTO Error Messages.
     *
     * @var MockObject|ErrorDtoFactoryInterface
     */
    private $errorFactory;

    /**
     * Error code classifier service.
     *
     * @var ClassifierService
     */
    private $classifierError;

    /**
     * Sets the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->errorFactory    = $this->getMockBuilder(ErrorDtoFactoryInterface::class)->getMock();
        $this->classifierError = new ClassifierService($this->errorFactory);
        $this->httpCode400     = 400;
        $this->errorCode100    = 100;
        $this->errorCode200    = 200;
        $this->someMsg         = 'Some message error';
    }

    /**
     * Cleans the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->errorFactory    = null;
        $this->classifierError = null;
        $this->httpCode400     = null;
        $this->errorCode100    = null;
        $this->errorCode200    = null;
        $this->someMsg         = null;
    }

    /**
     * Testing key availability.
     */
    public function testHas()
    {
        $errorData = [
            ErrorProviderInterface::MESSAGE_FIELD   => $this->someMsg,
            ErrorProviderInterface::HTTP_CODE_FIELD => $this->httpCode400,
        ];
        $data      = [$this->errorCode100 => $errorData];

        $this->assertFalse($this->classifierError->has($this->errorCode100));
        $this->assertFalse($this->classifierError->has($this->errorCode200));

        $this->classifierError->addProvider($this->getErrorProviderMock(1, $data));

        $this->assertTrue($this->classifierError->has($this->errorCode100));
        $this->assertFalse($this->classifierError->has($this->errorCode200));
    }

    /**
     * Testing data retrieval.
     */
    public function testGet()
    {
        $errorData = [
            ErrorProviderInterface::MESSAGE_FIELD   => $this->someMsg,
            ErrorProviderInterface::HTTP_CODE_FIELD => $this->httpCode400,
        ];
        $data      = [$this->errorCode100 => $errorData];

        $error = $this->getMockBuilder(ErrorInterface::class)->disableOriginalConstructor()->getMock();
        $this->errorFactory
            ->expects($this->once())
            ->method('createDto')
            ->with($this->errorCode100, $errorData)
            ->willReturn($error);

        $this->classifierError->addProvider($this->getErrorProviderMock(1, $data));

        $dto = $this->classifierError->get($this->errorCode100);

        $this->assertInstanceOf(ErrorInterface::class, $dto);
    }

    /**
     * Tests a keyless error when receiving data.
     */
    public function testGetErrorCodeNotFound()
    {
        $errorCode = $this->errorCode100;

        $this->expectException(EntryNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Code %s not found in error registry', $errorCode));

        $this->classifierError->get($errorCode);
    }

    /**
     * Тестирует получение списка всех ошибок
     */
    public function testGetAll()
    {
        $errorData = [
            ErrorProviderInterface::MESSAGE_FIELD   => $this->someMsg,
            ErrorProviderInterface::HTTP_CODE_FIELD => $this->httpCode400,
        ];
        $data      = [$this->errorCode100 => $errorData];

        $this->classifierError->addProvider($this->getErrorProviderMock(1, $data));

        $errorArray = $this->classifierError->getAll();

        $this->assertTrue(is_array($errorArray));
        $this->assertArrayHasKey($this->errorCode100, $errorArray);

        $errorDataArray = $errorArray[$this->errorCode100];

        $this->assertArrayHasKey(ErrorProviderInterface::MESSAGE_FIELD, $errorDataArray);
        $this->assertEquals($this->someMsg, $errorDataArray[ErrorProviderInterface::MESSAGE_FIELD]);
        $this->assertArrayHasKey(ErrorProviderInterface::HTTP_CODE_FIELD, $errorDataArray);
        $this->assertEquals($this->httpCode400, $errorDataArray[ErrorProviderInterface::HTTP_CODE_FIELD]);
    }

    /**
     * Tests intersecting key error when adding an error providerю
     */
    public function testAddProviderIntersectedKeys()
    {
        $errorData = [
            ErrorProviderInterface::MESSAGE_FIELD   => $this->someMsg,
            ErrorProviderInterface::HTTP_CODE_FIELD => $this->httpCode400,
        ];
        $data      = [$this->errorCode100 => $errorData];

        $errorProviderMock = $this->getErrorProviderMock(2, $data);
        $this->classifierError->addProvider($errorProviderMock);

        $this->expectException(IntersectedKeysFoundException::class);
        $this->expectExceptionMessageRegExp('/The intersecting keys '.$this->errorCode100.' were found in provider [^\s]+/');

        $this->classifierError->addProvider($errorProviderMock);
    }

    /**
     * Returns the error provider stub.
     *
     * @param integer $methodCount
     * @param array   $data
     *
     * @return ErrorProviderInterface
     */
    private function getErrorProviderMock(int $methodCount, array $data): ErrorProviderInterface
    {
        $mock = $this->getMockBuilder(ErrorProviderInterface::class)->getMock();
        $mock
            ->expects($this->exactly($methodCount))
            ->method('getErrors')
            ->willReturn($data);

        /** @var ErrorProviderInterface $mock */
        return $mock;
    }
}
