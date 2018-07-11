<?php

namespace CreativeNotes\Application\Service\NoteModule\Response;

use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class GetResponse
 *
 * @package CreativeNotes\Application\Service\NoteModule\Response
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetResponse implements ServiceResponseInterface
{
    /**
     * Notes collection
     *
     * @var array
     */
    private $notes;

    /**
     * Returns notes collection
     *
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * Sets notes collection
     *
     * @param array $notes
     * @return GetResponse
     */
    public function setNotes(array $notes): GetResponse
    {
        $this->notes = $notes;

        return $this;
    }
}