<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use Yggdrasil\Core\Service\ServiceResponseInterface;
use CreativeNotes\Domain\Entity\Note;

/**
 * Class EditResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class EditResponse implements ServiceResponseInterface
{
    /**
     * Result of service processing
     *
     * @var bool
     */
    private $success;

    /**
     * Edited note
     *
     * @var Note
     */
    private $note;

    /**
     * EditResponse constructor.
     *
     * Sets $success default value
     */
    public function __construct()
    {
        $this->success = false;
    }

    /**
     * Returns result of service processing
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Sets result of service processing
     *
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * Returns edited note
     *
     * @return Note
     */
    public function getNote(): Note
    {
        return $this->note;
    }

    /**
     * Sets edited note
     *
     * @param Note $note
     */
    public function setNote(Note $note): void
    {
        $this->note = $note;
    }
}