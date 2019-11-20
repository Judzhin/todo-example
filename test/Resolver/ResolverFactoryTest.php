<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest\Resolver;

use Doctrine\ORM\EntityManagerInterface;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use TODO\Resolver\PersistResolver;
use TODO\Resolver\ResolverFactory;
use TODO\Resolver\ResolverInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ResolverFactoryTest
 * @package TODOTest\Resolver
 */
class ResolverFactoryTest extends TestCase
{
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('doctrine.entity_manager.orm_default')
            ->willReturn($this->prophesize(EntityManagerInterface::class));

        /** @var FactoryInterface $factory */
        $factory = new ResolverFactory;

        /** @var ResolverInterface $resolver */
        $resolver = $factory($container->reveal(), PersistResolver::class);

        $this->assertInstanceOf(ResolverInterface::class, $resolver);
    }
}
