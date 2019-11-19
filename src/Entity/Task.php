<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Task
 *
 * @ORM\Entity()
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue("NONE")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", name="`check`")
     */
    private $check = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="lock_by", nullable=true)
     */
    private $lockBy = null;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", name="lock_at", nullable=true)
     */
    private $lockAt = null;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     * @return Task
     */
    public function setId(UuidInterface $id): Task
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCheck(): int
    {
        return $this->check;
    }

    /**
     * @param int $check
     * @return Task
     */
    public function setCheck(int $check): Task
    {
        $this->check = $check;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Task
     */
    public function setDescription(string $description): Task
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLockBy(): ?string
    {
        return $this->lockBy;
    }

    /**
     * @param string|null $lockBy
     * @return Task
     */
    public function setLockBy(string $lockBy = null): Task
    {
        $this->lockBy = $lockBy;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getLockAt(): ?\DateTimeImmutable
    {
        return $this->lockAt;
    }

    /**
     * @param \DateTimeImmutable|null $lockAt
     * @return Task
     */
    public function setLockAt(\DateTimeImmutable $lockAt = null): Task
    {
        $this->lockAt = $lockAt;
        return $this;
    }
}