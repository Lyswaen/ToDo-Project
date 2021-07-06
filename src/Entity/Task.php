<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class is the representation of a Task in the Database.
 *
 * @package App\Entity
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * The $id property is the primary key and it is auto-generated.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The $createAt property represent the creation date of a task.
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * The $title property represent the title of a task.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * The $content property represent the $content of a task.
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * The $isDone property represent the status of a task (if it is done or not).
     *
     * @ORM\Column(type="boolean")
     */
    private int $isDone = 0;

    /**
     * The $user property represent the author of a task.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function IsDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * @param $flag
     */
    public function toggle($flag)
    {
        $this->isDone = $flag;
    }

    /**
     * @return \App\Entity\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param \App\Entity\User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param int $isDone
     * @return \App\Entity\Task
     */
    public function setIsDone(int $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }
}
