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
 * Class MessageResolverFactory
 * @package TODO
 */
class MessageResolverFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|MessageResolver
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')['resolvers'];

        /** @var MessageResolver $messageResolver */
        $messageResolver = new MessageResolver;

        /**
         * @var string $name
         * @var int $priority
         */
        foreach ($config as $name => $priority) {
            $messageResolver->attach($container->get($name), $priority);
        }

        return $messageResolver;
    }
}