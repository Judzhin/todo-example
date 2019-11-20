<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest\Handler;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use TODO\Handler\HomePageHandler;
use TODO\Handler\HomePageHandlerFactory;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class HomePageHandlerFactoryTest
 * @package TODOTest\Handler
 */
class HomePageHandlerFactoryTest extends TestCase
{
    public function testInstance()
    {
        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var RouterInterface $router */
        $router = $this->prophesize(RouterInterface::class);
        $container
            ->get(RouterInterface::class)
            ->willReturn($router);

        /** @var TemplateRendererInterface $template */
        $template = $this->prophesize(TemplateRendererInterface::class);

        $container
            ->get(TemplateRendererInterface::class)
            ->willReturn($template);

        /** @var EntityManagerInterface $objectManager */
        $objectManager = $this->prophesize(EntityManagerInterface::class);

        $container
            ->get('doctrine.entity_manager.orm_default')
            ->willReturn($objectManager);

        /** @var HomePageHandlerFactory $factory */
        $factory = new HomePageHandlerFactory;

        $this->assertInstanceOf(HomePageHandlerFactory::class, $factory);

        /** @var HomePageHandler $homePage */
        $homePage = $factory($container->reveal(), HomePageHandler::class);

        $this->assertInstanceOf(HomePageHandler::class, $homePage);
    }


}
