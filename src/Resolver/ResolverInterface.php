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
    /** @const OPERATION_ADD */
    public const OPERATION_ADD = 'record.add';

    /** @const OPERATION_CHECK */
    public const OPERATION_CHECK = 'record.check';

    /** @const OPERATION_EDIT */
    public const OPERATION_EDIT = 'record.edit';

    /** @const OPERATION_REJECT */
    public const OPERATION_REJECT = 'record.reject';

    /** @const OPERATION_COMMIT */
    public const OPERATION_COMMIT = 'record.commit';

    /** @const OPERATION_DROP */
    public const OPERATION_DROP = 'record.drop';

    /**
     * @param array $values
     * @return bool
     */
    public function resolve(array $values): bool;
}