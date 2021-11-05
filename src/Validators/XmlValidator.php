<?php

namespace aik27\DromClient\Validators;

use aik27\DromClient\Interfaces\ValidatorInterface;
use DOMDocument;

/**
 * XML validator for server response
 */

class XmlValidator implements ValidatorInterface
{
    public string $version = '1.0';
    public string $encoding = 'utf-8';

    /**
     * {@inheritdoc}
     */

    public function validate(string $content): bool
    {
        if (trim($content) === '') {
            return false;
        }

        libxml_use_internal_errors(true);

        $doc = new DOMDocument($this->version, $this->encoding);
        $doc->loadXML($content);

        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }
}
