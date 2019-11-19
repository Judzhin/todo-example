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
 * Class RemoveResolver
 * @package TODO\Resolver
 */
class RemoveResolver extends AbstractResolver
{
    /** @var array  */
    private $operations = [
        'record.drop',
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

        /** @var array $data */
        $data = $values['data'];

        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var Task $entity */
        if ($entity = $objectManager->find(Task::class, $data['id'])) {
            $objectManager->remove($entity);
            $objectManager->flush();
        };

        return true;
    }
}