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
use TODO\MessageComponentFactory;
use TODO\MessageResolver;
use TODO\MessageResolverFactory;
use TODO\Resolver\ResolverInterface;

/**
 * Class MessageResolverFactoryTest
 * @package TODOTest
 */
class MessageResolverFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')
            ->willReturn([
                'resolvers' => [
                    ResolverInterface::class => 100
                ]
            ]);

        $container
            ->get(ResolverInterface::class)
            ->willReturn($this->prophesize(ResolverInterface::class));

        /** @var MessageComponentFactory $factory */
        $factory = new MessageResolverFactory;

        /** @var MessageResolver $messageResolver */
        $messageResolver = $factory->__invoke($container->reveal(), MessageResolver::class);

        $this->assertInstanceOf(MessageResolver::class, $messageResolver);

    }
}
