<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

use Yggdrasil\Core\Service\ServiceRequestInterface;

/**
 * Class GetRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetRequest implements ServiceRequestInterface
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
     * @return null|string
     */
    public function getSearchTerm(): ?string
    {
        return $this->searchTerm;
    }

    /**
     * Sets search term
     *
     * @param string $searchTerm
     */
    public function setSearchTerm(string $searchTerm): void
    {
        $this->searchTerm = $searchTerm;
    }
}