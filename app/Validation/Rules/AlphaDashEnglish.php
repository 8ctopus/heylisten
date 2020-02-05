<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaDashEnglish implements Rule
{
    public function passes($attribute, $value)
    {
        return !preg_match('/[^a-z_\-0-9]/i', $value);
    }

    public function message()
    {
        return trans('validation.custom.alpha_dash_english');
    }
}
