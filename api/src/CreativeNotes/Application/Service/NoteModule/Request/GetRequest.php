<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

/**
 * Class GetRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetRequest
{
    /**
     * Optional search term
     *
     * @var string
     */
    private $searchTerm;

    /**
     * Returns search term
     *
     * @return string?
     */
    public function getSearchTerm(): ?string
    {
        return $this->searchTerm;
    }

    /**
     * Sets search term
     *
     * @param string $searchTerm
     * @return GetRequest
     */
    public function setSearchTerm(string $searchTerm): GetRequest
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }
}