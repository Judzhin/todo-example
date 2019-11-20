<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest\Handler;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use TODO\Entity\Task;
use TODO\Handler\HomePageHandler;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class HomePageHandlerTest
 * @package TODOTest\Handler
 */
class HomePageHandlerTest extends TestCase
{
    public function testCallHandlerMethod()
    {
        /** @var RouterInterface $router */
        $router = $this->prophesize(RouterInterface::class);

        /** @var TemplateRendererInterface $renderer */
        $renderer = $this->prophesize(TemplateRendererInterface::class);

        /** @var EntityManagerInterface $objectManager */
        $objectManager = $this->prophesize(EntityManagerInterface::class);

        /** @var ObjectRepository $objectRepository */
        $objectRepository = $this->prophesize(EntityRepository::class);
        $objectRepository->findAll()->willReturn([
            (new Task)
                ->setId(Uuid::uuid4())
                ->setCheck(0)
                ->setDescription('Some Description')
                ->setLockBy(null)
                ->setLockAt(new \DateTimeImmutable)
        ]);

        $objectManager
            ->getRepository(Task::class)
            ->willReturn($objectRepository);

        $renderer
            ->render('app::home-page', Argument::type('array'))
            ->willReturn('');

        /** @var RequestHandlerInterface $handler */
        $handler = new HomePageHandler(
            $router->reveal(),
            $renderer->reveal(),
            $objectManager->reveal()
        );

        /** @var HtmlResponse $response */
        $response = $handler->handle(
            $this->prophesize(ServerRequestInterface::class)->reveal()
        );

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
