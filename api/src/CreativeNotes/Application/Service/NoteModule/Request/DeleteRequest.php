<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

use Yggdrasil\Core\Service\ServiceRequestInterface;

/**
 * Class DeleteRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class DeleteRequest implements ServiceRequestInterface
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