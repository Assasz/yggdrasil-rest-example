<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

use Yggdrasil\Core\Service\ServiceRequestInterface;

/**
 * Class GetOneRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetOneRequest implements ServiceRequestInterface
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
     */
    public function setNoteId(int $noteId): void
    {
        $this->noteId = $noteId;
    }
}