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
use TODO\Entity\Task;
use TODO\Resolver\PersistResolver;
use TODO\Resolver\ResolverInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Class PersistResolverTest
 * @package TODOTest\Resolver
 */
class PersistResolverTest extends TestCase
{
    protected $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var EntityManagerInterface|ObjectProphecy $objectManager */
        $objectManager = $this->prophesize(EntityManagerInterface::class);

        /** @var HydratorInterface|ObjectProphecy $hydrator */
        $hydrator = $this->prophesize(DoctrineObject::class);
        $hydrator
            ->hydrate(
                Argument::type('array'),
                Argument::type(Task::class)
            )
            ->willReturn($this->prophesize(Task::class));

        $objectManager
            ->persist(Argument::type(Task::class))
            ->willReturn();

        $objectManager
            ->flush()
            ->willReturn();

        $this->resolver = new PersistResolver(
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
            'operation' => ResolverInterface::OPERATION_ADD,
            'data' => [
                // ...
            ]
        ]));
    }
}
