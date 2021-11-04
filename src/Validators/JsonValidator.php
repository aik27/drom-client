<?php

namespace aik27\DromClient\Validators;

use aik27\DromClient\Interfaces\ValidatorInterface;

class JsonValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */

    public function validate(string $content): bool
    {
        if (trim($content) === '') {
            return false;
        }

        json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return true;
    }
}
