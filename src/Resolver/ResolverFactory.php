<?php
/**
 * Created by PhpStorm.
 * User: judzhin
 * Date: 20.11.2019
 * Time: 18:56
 */

namespace TODO\Resolver;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ResolverFactory
 * @package TODO\Resolver
 */
class ResolverFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $objectManager = $container->get('doctrine.entity_manager.orm_default'),
            new DoctrineObject($objectManager)
        );
    }
}