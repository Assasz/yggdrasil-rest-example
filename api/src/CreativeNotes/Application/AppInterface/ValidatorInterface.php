<?php

namespace CreativeNotes\Application\AppInterface;

/**
 * Interface ValidatorInterface
 *
 * @package CreativeNotes\Application\AppInterface
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
interface ValidatorInterface
{
    /**
     * Checks if given entity object is valid or not
     *
     * @param object $entity
     * @return bool
     */
    public function isValid(object $entity): bool;
}