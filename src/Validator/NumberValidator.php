<?php

namespace Restugbk\Validator;

use Exception;

class NumberValidator
{
    public static function validate(string $number): string
    {
        if (empty($number)) {
            throw new Exception("Number is not defined");
        }

        $number = str_replace(['-', ' '], '', $number);

        if (substr($number, 0, 1) === '0') {
            $number = '+62' . substr($number, 1);
        }

        if (substr($number, 0, 2) === '62') {
            $number = '+' . $number;
        }

        return $number;
    }
}