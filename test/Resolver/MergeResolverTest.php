<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest\Resolver;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;
use TODO\Entity\Task;
use TODO\Resolver\MergeResolver;
use TODO\Resolver\ResolverInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Class MergeResolverTest
 * @package TODOTest\Resolver
 */
class MergeResolverTest extends TestCase
{
    protected $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var EntityManagerInterface|ObjectProphecy $objectManager */
        $objectManager = $this->prophesize(EntityManagerInterface::class);

        /** @var Task|ObjectProphecy $entity */
        $entity = $this->prophesize(Task::class);

        $objectManager
            ->find(
                Argument::type('string'),
                Argument::type('string')
            )
            ->willReturn($entity);

        /** @var HydratorInterface|ObjectProphecy $hydrator */
        $hydrator = $this->prophesize(DoctrineObject::class);
        $hydrator
            ->hydrate(
                Argument::type('array'),
                Argument::type(Task::class)
            )
            ->willReturn($entity);

        $objectManager
            ->merge(Argument::type(Task::class))
            ->willReturn();

        $objectManager
            ->flush()
            ->willReturn();

        $this->resolver = new MergeResolver(
            $objectManager->reveal(),
            $hydrator->reveal()
        );
    }

    public function testCallResolveMethodWithWrongValues()
    {
        $this->assertFalse($this->resolver->resolve(['operation' => 'wrong']));
    }

    public function testCallResolveMethodWithCorrectlyValues()
    {
        $this->assertTrue($this->resolver->resolve([
            'operation' => ResolverInterface::OPERATION_EDIT,
            'data' => [
                'id' => Uuid::uuid4()->toString()
            ]
        ]));
    }
}
