<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO;

use Doctrine\ORM\Tools;
use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ApplicationFactory
 * @package TODO
 */
class ApplicationFactory implements FactoryInterface
{

    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|Application
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Application $cli */
        $cli = new Application('Application console');

        /** @var \Doctrine\Common\Persistence\ObjectManager|\Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.entity_manager.orm_default');

        /** @var \Symfony\Component\Console\Helper\HelperSet $helperSet */
        $helperSet = $cli->getHelperSet();
        $helperSet->set(new Tools\Console\Helper\EntityManagerHelper($entityManager), 'em');

        Tools\Console\ConsoleRunner::addCommands($cli);

        $cli->setCommandLoader(new ContainerCommandLoader($container, $config['commands'] ?? []));

        return $cli;
    }
}