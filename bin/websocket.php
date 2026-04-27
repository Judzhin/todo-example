<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

use Psr\Container\ContainerInterface;

(function () {
    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    // /** @var int $i */
    // $i = 1;
    //
    // while (true) {
    //     file_put_contents('./data/logs/message.log', 'Hello ' . ++$i . PHP_EOL, FILE_APPEND);
    //     sleep(3);
    // }

    /** @var \Ratchet\Server\IoServer $server */
    $server = $container->get(\Ratchet\Server\IoServer::class);
    $server->run();

})();