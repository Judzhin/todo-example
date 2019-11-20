<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODOTest\Entity;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TODO\Entity\Task;

/**
 * Class TaskTest
 * @package TODOTest\Entity
 */
class TaskTest extends TestCase
{
    public function testEntity()
    {
        /** @var Task $entity */
        $entity = (new Task)
            ->setId($id = Uuid::uuid4())
            ->setCheck($check = 0)
            ->setDescription($description = 'Some Description')
            ->setLockBy($lockBy = null)
            ->setLockAt($lockAt = null);

        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($check, $entity->getCheck());
        $this->assertEquals($description, $entity->getDescription());
        $this->assertEquals($lockBy, $entity->getLockBy());
        $this->assertEquals($lockAt, $entity->getLockAt());

        $entity->setLockBy($lockBy = Uuid::uuid4()->toString());
        $entity->setLockAt($lockAt = new \DateTimeImmutable);

        $this->assertEquals($lockBy, $entity->getLockBy());
        $this->assertEquals($lockAt, $entity->getLockAt());
    }
}