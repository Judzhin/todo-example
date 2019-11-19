<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace TODO\Resolver;

/**
 * Interface ResolverInterface
 * @package TODO\Resolver
 */
interface ResolverInterface
{
    /**
     * @param array $values
     * @return bool
     */
    public function resolve(array $values): bool;
}