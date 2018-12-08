<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use CreativeNotes\Domain\Entity\Note;

/**
 * Class GetOneResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetOneResponse
{
    /**
     * Result of service processing
     *
     * @var bool
     */
    private $success;

    /**
     * Note returned by service
     *
     * @var Note
     */
    private $note;

    /**
     * GetOneResponse constructor.
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
     * @return GetOneResponse
     */
    public function setSuccess(bool $success): GetOneResponse
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Returns note
     *
     * @return Note
     */
    public function getNote(): Note
    {
        return $this->note;
    }

    /**
     * Sets note
     *
     * @param Note $note
     * @return GetOneResponse
     */
    public function setNote(Note $note): GetOneResponse
    {
        $this->note = $note;

        return $this;
    }
}