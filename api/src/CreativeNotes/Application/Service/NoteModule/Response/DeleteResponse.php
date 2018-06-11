<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class DeleteResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class DeleteResponse implements ServiceResponseInterface
{
    /**
     * Result of service processing
     *
     * @var bool
     */
    private $success;

    /**
     * DeleteResponse constructor.
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
}