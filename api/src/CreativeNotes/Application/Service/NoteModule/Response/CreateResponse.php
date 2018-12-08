<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use CreativeNotes\Domain\Entity\Note;

/**
 * Class CreateResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class CreateResponse
{
    /**
     * Result of service processing
     *
     * @var bool
     */
    private $success;

    /**
     * Created note
     *
     * @var Note
     */
    private $note;

    /**
     * CreateResponse constructor.
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
     * @return CreateResponse
     */
    public function setSuccess(bool $success): CreateResponse
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Returns created note
     *
     * @return Note
     */
    public function getNote(): Note
    {
        return $this->note;
    }

    /**
     * Sets created note
     *
     * @param Note $note
     * @return CreateResponse
     */
    public function setNote(Note $note): CreateResponse
    {
        $this->note = $note;

        return $this;
    }
}