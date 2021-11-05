<?php

namespace aik27\DromClient\Interfaces;

/**
 * Specify expected content type of server response
 */

interface ValidatorInterface
{
    /**
     * Validate response
     *
     * @param string $content
     * @return bool
     */
    public function validate(string $content): bool;
}