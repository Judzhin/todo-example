<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Application;
use TODO\ApplicationFactory;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ApplicationFactoryTest
 * @package TODOTest
 */
class ApplicationFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('doctrine.entity_manager.orm_default')
            ->willReturn($this->prophesize(EntityManagerInterface::class));

        /** @var FactoryInterface $factory */
        $factory = new ApplicationFactory;

        /** @var Application $cli */
        $cli = $factory($container->reveal(), Application::class);

        $this->assertInstanceOf(Application::class, $cli);
    }
}
