<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TODO\Entity\Task;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class HomePageHandler
 * @package TODO\Handler
 */
class HomePageHandler implements RequestHandlerInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /**
     * HomePageHandler constructor.
     * @param Router\RouterInterface $router
     * @param TemplateRendererInterface $template
     * @param ObjectManager $objectManager
     */
    public function __construct(
        Router\RouterInterface $router,
        TemplateRendererInterface $template,
        ObjectManager $objectManager
    )
    {
        $this->router = $router;
        $this->template = $template;
        $this->setObjectManager($objectManager);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        // /** @var Task $entity */
        // $entity = (new Task)
        //     ->setCheck(0)
        //     ->setDescription('Buy a plane ticket to Vienna and back')
        // ;
        //
        // $this->getObjectManager()->persist($entity);
        // $this->getObjectManager()->flush();

        /** @var ObjectRepository $repository */
        $repository = $this->getObjectManager()->getRepository(Task::class);

        // var_dump([
        //     'data' => array_map(function (Task $task) {
        //         return [
        //             'id' => $task->getId()->toString(),
        //             'check' => $task->getCheck(),
        //             'description' => $task->getDescription(),
        //             'lockBy' => $task->getLockBy(),
        //             'lockAt' => ($task->getLockAt() instanceof \DateTimeInterface)
        //                 ? $task->getLockAt()->format('Y-m-d H:i:s') : null
        //         ];
        //     }, $repository->findAll())
        // ]); die();

        return new HtmlResponse($this->template->render('app::home-page', [
            'data' => array_map(function (Task $task) {
                return [
                    'id' => $task->getId()->toString(),
                    'check' => $task->getCheck(),
                    'description' => $task->getDescription(),
                    'lockBy' => $task->getLockBy(),
                    'lockAt' => ($task->getLockAt() instanceof \DateTimeInterface)
                        ? $task->getLockAt()->format('Y-m-d H:i:s') : null
                ];
            }, $repository->findAll()),
            // 'data' => [
            //     [
            //         'id' => 'some-id-todo-record-1',
            //         'check' => 0,
            //         'description' => 'Some Description About ToDo 1',
            //         'lockBy' => '',
            //         'lockAt' => '2018-12-31 23:59:59'
            //     ], [
            //         'id' => 'some-id-todo-record-2',
            //         'check' => 1,
            //         'description' => 'Some Description About ToDo 2',
            //         'lockBy' => '',
            //         'lockAt' => '2019-12-31 23:59:59'
            //     ], [
            //         'id' => 'some-id-todo-record-3',
            //         'check' => 1,
            //         'description' => 'Some Description About ToDo 3',
            //         'lockBy' => '',
            //         'lockAt' => '2019-12-31 23:59:59'
            //     ]
            // ]
        ]));
    }

}