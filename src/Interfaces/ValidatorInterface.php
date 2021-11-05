<?php

namespace aik27\DromClient\Interfaces;

interface ValidatorInterface
{
    /**
     * Validate response content
     *
     * @param string $content
     * @return bool
     */
    public function validate(string $content): bool;
}