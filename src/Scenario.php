<?php

namespace aik27\DromClient;

/**
 * Describes validation rules of client data for specific case.
 *
 * Configured through the constructor by array with structure: field_name => array [some rules]
 *
 * Example:
 *
 * ```php
 *  $create = new Scenario([
 *       'name' => [
 *           'type' => 'string',
 *           'required' => true,
 *       ],
 *       'text' => [
 *           'type' => 'string',
 *           'required' => true,
 *       ],
 *  ]);
 * ```
 *
 * Available params to configure rules:
 * ```text
 * + type - int|string - expected data type
 * + required - true|false - field exists and not empty
 * ```
 */

class Scenario
{
    protected array $fields = [];

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function checkFieldsDifference(array $data): void
    {
        $scenarioFieldNames = [];
        foreach ($this->fields as $name => $rules) {
            $scenarioFieldNames[] = $name;
        }

        $dataFieldNames = [];
        foreach ($data as $key => $value) {
            $dataFieldNames[] = $key;
        }

        $diffNames = array_diff($dataFieldNames, $scenarioFieldNames);

        if (!empty($diffNames)) {
            $diffNames = implode(', ', $diffNames);
            throw new \Exception('Field(s) not present in active Scenario: ' . $diffNames);
        }
    }

    public function checkData(array $data): void
    {
        foreach ($this->fields as $name => $rules) {
            if (!isset($rules['required'])) {
                $rules['required'] = false;
            }

            if (empty($rules['type'])) {
                throw new \Exception('Type is required for field ' . $name);
            }

            $required = false;

            foreach ($data as $key => $value) {
                if ($name === $key) {
                    if ($rules['required'] === true) {
                        $required = true;
                        if (empty($value)) {
                            throw new \Exception($key . ' field is required');
                        }
                    }

                    switch ($rules['type']) {
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

            if ($required !== $rules['required']) {
                throw new \Exception($name . ' field is required');
            }
        }
    }
}
