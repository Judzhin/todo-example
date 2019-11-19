<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

declare(strict_types=1);

namespace TODO;

use Interop\Container\ContainerInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IoServerFactory
 * @package TODO
 */
class IoServerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|IoServer
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        return IoServer::factory(
            new HttpServer(
                new WsServer(
                    $container->get(MessageComponent::class)
                )
            ), 9001 // TODO: make change by config
        );
    }
}