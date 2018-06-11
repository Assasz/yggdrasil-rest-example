<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use CreativeNotes\Domain\Entity\Note;
use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class CreateResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class CreateResponse implements ServiceResponseInterface
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
     * Sets $result default value
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
     */
    public function setNote(Note $note): void
    {
        $this->note = $note;
    }
}