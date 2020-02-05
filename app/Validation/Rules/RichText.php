<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class RichText implements Rule
{
    public function passes($attribute, $value) {
        return $value == strip_tags($value, '<h1><h2><h3><p><span><ol><ul><li><em><s><u><strong><br><pre><blockquote>');
    }

    public function message() {
        return trans('validation.custom.rich-text');
    }

    public static function cleanEmptyLines($html) {
        // remove <p></p> && <p><br></p> before text
        $clean = preg_replace('/^(<p>\s*(<br>)?\s*<\/p>)+/i', '', $html);

        // after text
        $clean = preg_replace('/(<p>\s*(<br>)?\s*<\/p>)+$/i', '', $clean);

        // remove more than two empty lines
        $clean = preg_replace('/(<p>\s*(<br>)?\s*<\/p>){2,}/i', '<p><br></p>', $clean);

        return $clean;
    }
}
