<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use TODO\MessageResolver;
use TODO\Resolver\ResolverInterface;

/**
 * Class MessageResolverTest
 * @package TODOTest
 */
class MessageResolverTest extends TestCase
{

    /** @var MessageResolver|ObjectProphecy */
    protected $messageResolver;

    /** @var ResolverInterface|ObjectProphecy */
    protected $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->messageResolver = new MessageResolver;
        $this->resolver = $this->prophesize(ResolverInterface::class);
    }

    /**
     *
     */
    public function testCallAttachMethod()
    {
        /** @var MessageResolver $itSelf */
        $itSelf = $this->messageResolver->attach(
            $this->resolver->reveal()
        );
        $this->assertInstanceOf(MessageResolver::class, $itSelf);
    }

    /**
     *
     */
    public function testCallResolveMethodWithWrongResponse()
    {
        $this->assertFalse($this->messageResolver->resolve([]));
    }

    /**
     *
     */
    public function testCallResolveMethodWithCorrectlyResponse()
    {
        $this->resolver
            ->resolve(Argument::type('array'))
            ->willReturn(true);

        $this->messageResolver->attach($this->resolver->reveal());

        $this->assertTrue($this->messageResolver->resolve([]));
    }
}
