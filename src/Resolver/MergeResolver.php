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
 * Class MergeResolver
 * @package TODO\Resolver
 */
class MergeResolver extends AbstractResolver
{
    /** @var array  */
    private $operations = [
        'record.check',
        'record.edit',
        'record.reject',
        'record.commit',
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
            /** @var Task $entity */
            $entity = $this->hydrator->hydrate($data, $entity);
            $objectManager->merge($entity);
            $objectManager->flush();
        };

        return true;
    }
}