<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainsKomplekOrPerumahan implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lowerValue = strtolower($value);
        if (strpos($lowerValue, 'komplek') === false && strpos($lowerValue, 'perumahan') === false) {
            $fail('Harus menyertakan kata "Komplek" atau "Perumahan".');
        }
    }
}