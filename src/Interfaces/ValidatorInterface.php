<?php

namespace aik27\DromClient\Interfaces;

interface ValidatorInterface
{
    /**
     * Validate content
     *
     * @param string $content
     * @return bool
     */
    public function validate(string $content): bool;
}