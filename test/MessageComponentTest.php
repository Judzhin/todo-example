<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ratchet\ConnectionInterface;
use TODO\MessageComponent;
use TODO\MessageResolver;
use Zend\InputFilter\InputFilterInterface;
use Zend\Json\Encoder;

/**
 * Class MessageComponentTest
 * @package TODOTest
 */
class MessageComponentTest extends TestCase
{

    /** @var InputFilterInterface|ObjectProphecy */
    protected $inputFilter;

    /** @var MessageResolver|ObjectProphecy */
    protected $messageResolver;

    /** @var MessageComponent */
    protected $messageComponent;

    /** @var ConnectionInterface|ObjectProphecy */
    protected $connection;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var InputFilterInterface $inputFilter */
        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $inputFilter
            ->setData(Argument::type('array'))
            ->willReturn($inputFilter);

        $this->inputFilter = $inputFilter;

        $this->messageResolver = $this->prophesize(MessageResolver::class);

        $this->messageComponent = new MessageComponent(
            $this->inputFilter->reveal(),
            $this->messageResolver->reveal()
        );

        $this->connection = $this->prophesize(ConnectionInterface::class);
    }

    public function testCallOnOpenMethod()
    {
        $this->messageComponent->onOpen(
            $this->prophesize(ConnectionInterface::class)->reveal()
        );

        $this->assertTrue(true);
    }

    public function testCallOnMessageMethodWithWrongData()
    {
        $this->inputFilter->isValid()->willReturn(false);
        $this->messageComponent->onMessage(
            $this->connection->reveal(),
            Encoder::encode([])
        );

        $this->assertFalse(false);
    }

    public function testCallOnMessageMethodWithCorrectlyData()
    {
        $this->inputFilter
            ->isValid()
            ->willReturn(true);

        $this->inputFilter
            ->getValues()
            ->willReturn([]);

        $this->messageResolver
            ->resolve(Argument::type('array'))
            ->willReturn(true);

        /** @var ConnectionInterface|ObjectProphecy $client */
        $client = $this->prophesize(ConnectionInterface::class);
        $client
            ->send(Argument::type('string'))
            ->willReturn();

        $this->messageComponent->onOpen($client->reveal());

        $this->messageComponent->onMessage(
            $this->connection->reveal(),
            Encoder::encode([])
        );

        $this->assertTrue(true);
    }

    public function testCallOnCloseMethod()
    {
        $this->messageComponent->onClose(
            $this->connection->reveal()
        );
        $this->assertTrue(true);
    }

    public function testCallOnErrorMethod()
    {
        $this->messageComponent->onError(
            $this->connection->reveal(),
            $this->prophesize(\Exception::class)->reveal()
        );
        $this->assertTrue(true);
    }

}
