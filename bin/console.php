<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

chdir(dirname(__DIR__));

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

(function () {
    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    /** @var Application $cli */
    $cli = $container->get(Application::class);
    $cli->run();
})();