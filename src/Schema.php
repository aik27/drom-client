<?php

namespace aik27\DromClient;

class Schema
{
    protected array $fields = [];

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function checkFields(array $fields, int $scenario)
    {
        foreach ($this->fields as $fieldName => $fieldRules) {
            if (!isset($fieldRules['required'])) {
                $fieldRules['required'] = false;
            }

            if (!isset($fieldRules['skipOnCreate'])) {
                $fieldRules['skipOnCreate'] = false;
            }

            if ($scenario === 1 and $fieldRules['skipOnCreate'] === true) {
                continue;
            }

            if (empty($fieldRules['type'])) {
                throw new \Exception('Type is required for field ' . $fieldName);
            }

            $required = false;

            foreach ($fields as $key => $value) {
                if ($fieldName === $key) {
                    if ($fieldRules['required'] === true) {
                        $required = true;
                        if (empty($value)) {
                            throw new \Exception($key . ' field is required');
                        }
                    }

                    switch ($fieldRules['type']) {
                        case 'int':
                            if (!is_int($value)) {
                                throw new \Exception($key . ' field must be an integer');
                            }
                            break;

                        case 'string':
                            if (!is_string($value)) {
                                throw new \Exception($key . ' field must be a string');
                            }
                            break;

                        default:
                            throw new \Exception('Unsupported type for field ' . $key);
                    }

                    break;
                }
            }

            if ($required !== $fieldRules['required']) {
                throw new \Exception($fieldName . ' field is required');
            }
        }
    }

    public function isDiffent(array $fields, int $scenario): bool
    {
        $schemaFields = $this->fields;

        switch ($scenario) {
            // create
            case 1:
                foreach ($schemaFields as $fieldName => $fieldRules) {
                    foreach ($fields as $key => $value) {
                        if ($fieldName === $key) {
                            if (!isset($fieldRules['skipOnCreate'])) {
                                $fieldRules['skipOnCreate'] = false;
                            }
                            if ($fieldRules['skipOnCreate'] === true) {
                                unset($schemaFields[$fieldName]);
                                break;
                            }
                        }
                    }
                }
            // update
            default:
        }

        $diff = array_diff($fields, $schemaFields);

        return count($diff) === 0;
    }
}
