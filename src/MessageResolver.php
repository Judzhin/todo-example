<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO;

use TODO\Resolver\ResolverInterface;
use Zend\Stdlib\PriorityQueue;

/**
 * Class MessageResolver
 * @package TODO
 */
class MessageResolver
{
    /** @var PriorityQueue */
    protected $queue;

    /**
     * MessageResolver constructor.
     * @param array $resolvers
     * @codeCoverageIgnore
     */
    public function __construct(array $resolvers = [])
    {
        $this->queue = new PriorityQueue;

        /** @var ResolverInterface $resolver */
        foreach ($resolvers as $resolver) {
            $this->attach($resolver);
        }
    }

    /**
     * @param ResolverInterface $resolver
     * @param int $priority
     * @return MessageResolver
     */
    public function attach(ResolverInterface $resolver, $priority = 1): self
    {
        $this->queue->insert($resolver, $priority);
        return $this;
    }

    /**
     * @param array $values
     * @return bool
     */
    public function resolve(array $values): bool
    {
        /** @var ResolverInterface $resolver */
        foreach ($this->queue as $resolver) {

            if ($resolver->resolve($values)) {
                return true;
            }
        }

        return false;
    }
}