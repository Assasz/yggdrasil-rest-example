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
     * Indicates if note was found
     *
     * @var bool
     */
    private $found;

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
        $this->found = true;
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
     * Returns true if note was found, false otherwise
     *
     * @return bool
     */
    public function isFound(): bool
    {
        return $this->found;
    }

    /**
     * Sets found flag
     *
     * @param bool $found
     * @return EditResponse
     */
    public function setFound(bool $found): EditResponse
    {
        $this->found = $found;

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