<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO\Resolver;

use Doctrine\Common\Persistence\ObjectManager;
use TODO\Entity\Task;

/**
 * Class PersistResolver
 * @package TODO\Resolver
 */
class PersistResolver extends AbstractResolver
{
    /** @var array  */
    private $operations = [
        'record.add'
    ];

    /**
     * @param array $values
     * @return bool
     */
    public function resolve(array $values): bool
    {
        if (!in_array($values['operation'], $this->operations)) {
            return false;
        }

        echo "Persist message\n";

        /** @var array $data */
        $data = $values['data'];

        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var Task $entity */
        $entity = $this->hydrator->hydrate($data, new Task);

        $objectManager->persist($entity);
        $objectManager->flush();

        return true;
    }
}