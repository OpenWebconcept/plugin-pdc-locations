<?php

namespace OWC\PDC\Locations\Traits;

trait TimeFormatDelimiter
{
    /**
     * Validate if value contains delimiter and return.
     * Otherwise return the default delimiter.
     */
    public function getDelimiter(string $value, string $toValidate, string $default = ':'): string
    {
        $check = strpos($value, $toValidate);

        return $check !== false ? $toValidate : $default;
    }
}
