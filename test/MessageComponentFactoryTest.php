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
use TODO\MessageComponent;
use TODO\MessageComponentFactory;
use TODO\MessageResolver;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\PluginManagerInterface;

/**
 * Class MessageComponentFactoryTest
 * @package TODOTest
 */
class MessageComponentFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var PluginManagerInterface|ObjectProphecy $inputFilterManager */
        $inputFilterManager = $this->prophesize(PluginManagerInterface::class);

        $inputFilterManager
            ->get(MessageComponent::class)
            ->willReturn($this->prophesize(InputFilterInterface::class));

        $container->get('InputFilterManager')
            ->willReturn($inputFilterManager);

        $container
            ->get(MessageResolver::class)
            ->willReturn($this->prophesize(MessageResolver::class));

        /** @var MessageComponentFactory $factory */
        $factory = new MessageComponentFactory;

        /** @var MessageComponent $messageComponent */
        $messageComponent = $factory->__invoke($container->reveal(), MessageComponent::class);

        $this->assertInstanceOf(MessageComponentInterface::class, $messageComponent);

    }
}
