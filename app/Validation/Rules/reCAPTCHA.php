<?php
namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class reCAPTCHA implements Rule
{
    public function passes($attribute, $value)
    {
        // if disabled return true
        if (config('recaptcha.disabled')) {
            return true;
        }

        $client = new Client;
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                    [
                        'secret' => config('recaptcha.secret'),
                        'response' => $value
                    ]
            ]
        );
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }

    public function message()
    {
        return 'Invalid captcha.';
    }
}
