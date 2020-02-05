<?php

return [
    'enabled'   => env('RECAPTCHA_ENABLED', false),
    'disabled'  => !env('RECAPTCHA_ENABLED', false),
    'key'       => env('RECAPTCHA_SITE_KEY'),
    'secret'    => env('RECAPTCHA_SECRET_KEY'),
];