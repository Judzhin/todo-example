<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use TODO\IoServerFactory;
use TODO\MessageComponent;
use TODO\MessageComponentFactory;
use TODO\MessageResolver;
use TODO\MessageResolverFactory;
use TODO\Resolver\ResolverInterface;

/**
 * Class IoServerFactoryTest
 * @package TODOTest
 */
class IoServerFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(MessageComponent::class)
            ->willReturn($this->prophesize(MessageComponentInterface::class));

        /** @var MessageComponentFactory $factory */
        $factory = new IoServerFactory;

        /** @var MessageResolver $server */
        $server = $factory->__invoke($container->reveal(), IoServer::class);

        $this->assertInstanceOf(IoServer::class, $server);
    }
}
