<?php

namespace aik27\DromClient;

class Utils
{
    public function objectToArray($data): array
    {
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = (is_array($value) || is_object($value)) ? $this->objectToArray($value) : $value;
            }
            return $result;
        }

        return $data;
    }
}
