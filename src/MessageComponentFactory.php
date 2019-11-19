<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

declare(strict_types=1);

namespace TODO;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class MessageComponentFactory
 * @package TODO
 */
class MessageComponentFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|MessageComponent
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MessageComponent(
            $container->get('InputFilterManager')->get($requestedName),
            $container->get(MessageResolver::class)
        );
    }
}