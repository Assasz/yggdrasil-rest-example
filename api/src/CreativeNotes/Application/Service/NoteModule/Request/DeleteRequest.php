<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

/**
 * Class DeleteRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class DeleteRequest
{
    /**
     * Note ID
     *
     * @var int
     */
    private $noteId;

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
     * @return DeleteRequest
     */
    public function setNoteId(int $noteId): DeleteRequest
    {
        $this->noteId = $noteId;

        return $this;
    }
}