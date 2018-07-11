<?php

namespace CreativeNotes\Domain\Entity;

use Yggdrasil\Component\DoctrineComponent\SerializableEntityInterface;

/**
 * Note Entity
 *
 * @Entity(repositoryClass="CreativeNotes\Application\Repository\NoteRepository")
 * @package CreativeNotes\Domain\Entity
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class Note implements SerializableEntityInterface
{
    /**
     * Note ID
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * Note title
     *
     * @Column(type="string", length=255)
     * @var string
     */
    private $title;

    /**
     * Note content
     *
     * @Column(type="text")
     * @var string
     */
    private $content;

    /**
     * Note create date
     *
     * @Column(type="datetime")
     * @var \DateTime
     */
    private $createDate;

    /**
     * Note constructor
     *
     * Sets note create date
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
    }

    /**
     * Returns note ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns note title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets note title
     *
     * @param string $title
     * @return Note
     */
    public function setTitle(string $title): Note
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Returns note content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets note content
     *
     * @param string $content
     * @return Note
     */
    public function setContent(string $content): Note
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Returns note create date
     *
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }
}