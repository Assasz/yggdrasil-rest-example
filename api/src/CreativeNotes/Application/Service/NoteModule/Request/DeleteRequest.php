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
     * Note ID to delete
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
     */
    public function setNoteId(int $noteId): void
    {
        $this->noteId = $noteId;
    }
}