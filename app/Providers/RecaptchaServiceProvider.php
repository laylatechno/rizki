<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class RecaptchaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('recaptcha', function ($attribute, $value) {
            $recaptcha = new \ReCaptcha\ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
            $response = $recaptcha->verify($value, request()->ip());
            return $response->isSuccess();
        });
    }
}