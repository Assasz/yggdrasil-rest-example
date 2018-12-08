<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

/**
 * Class GetOneRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetOneRequest
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
     * @return GetOneRequest
     */
    public function setNoteId(int $noteId): GetOneRequest
    {
        $this->noteId = $noteId;

        return $this;
    }
}