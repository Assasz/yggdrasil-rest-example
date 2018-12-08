<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use CreativeNotes\Domain\Entity\Note;

/**
 * Class EditResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class EditResponse
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
     * @return EditResponse
     */
    public function setSuccess(bool $success): EditResponse
    {
        $this->success = $success;

        return $this;
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
     * @return EditResponse
     */
    public function setNote(Note $note): EditResponse
    {
        $this->note = $note;

        return $this;
    }
}