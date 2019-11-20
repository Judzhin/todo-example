<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO\Resolver;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Hydrator\HydratorInterface;

/**
 * Class AbstractResolver
 * @package TODO\Resolver
 */
abstract class AbstractResolver implements ResolverInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /** @var HydratorInterface */
    protected $hydrator;

    /**
     * AbstractResolver constructor.
     * @param ObjectManager $objectManager
     * @param HydratorInterface $hydrator
     */
    public function __construct(ObjectManager $objectManager, HydratorInterface $hydrator)
    {
        $this->setObjectManager($objectManager);
        $this->hydrator = $hydrator;
    }
}