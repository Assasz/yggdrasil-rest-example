<?php

namespace CreativeNotes\Application\Service\NoteModule\Request;

use Yggdrasil\Core\Service\ServiceRequestInterface;

/**
 * Class CreateRequest
 *
 * @package CreativeNotes\Application\Service\NoteModule\Request
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class CreateRequest implements ServiceRequestInterface
{
    /**
     * Note title
     *
     * @var string
     */
    private $title;

    /**
     * Note content
     *
     * @var string
     */
    private $content;

    /**
     * Returns note title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets note title
     *
     * @param string $title
     * @return CreateRequest
     */
    public function setTitle(string $title): CreateRequest
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Returns note content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets note content
     *
     * @param string $content
     * @return CreateRequest
     */
    public function setContent(string $content): CreateRequest
    {
        $this->content = $content;

        return $this;
    }
}