<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

use Yggdrasil\Core\Service\ServiceRequestInterface;

/**
 * Class EditRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class EditRequest implements ServiceRequestInterface
{
    /**
     * Note ID
     *
     * @var int
     */
    private $noteId;

    /**
     * Note title
     *
     * @var string
     */
    private $title;

    /**
     * Note content
     *
     * @var string
     */
    private $content;

    /**
     * Returns note ID
     *
     * @return int
     */
    public function getNoteId(): int
    {
        return $this->noteId;
    }

    /**
     * Sets note ID
     *
     * @param int $noteId
     * @return EditRequest
     */
    public function setNoteId(int $noteId): EditRequest
    {
        $this->noteId = $noteId;

        return $this;
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
     * @return EditRequest
     */
    public function setTitle(string $title): EditRequest
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
     * @return EditRequest
     */
    public function setContent(string $content): EditRequest
    {
        $this->content = $content;

        return $this;
    }
}