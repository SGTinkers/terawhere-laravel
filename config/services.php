<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Third Party Services
  |--------------------------------------------------------------------------
  |
  | This file is for storing the credentials for third party services such
  | as Stripe, Mailgun, SparkPost and others. This file provides a sane
  | default location for this type of information, allowing packages
  | to have a conventional place to find your various credentials.
  |
   */

  'mailgun'   => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
  ],

  'ses'       => [
    'key'    => env('SES_KEY'),
    'secret' => env('SES_SECRET'),
    'region' => 'us-east-1',
  ],

  'sparkpost' => [
    'secret' => env('SPARKPOST_SECRET'),
  ],

  'stripe'    => [
    'model'  => App\User::class,
    'key'    => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
  ],

  'facebook'  => [
    'model'         => App\User::class,
    'client_id'     => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect'      => env('APP_URL') . '/auth/callback?service=facebook',
  ],

  'google'    => [
    'model'         => App\User::class,
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('APP_URL') . '/auth/callback?service=google',
  ],
    'botman' => [
        'telegram_token' => env('TELEGRAM_TOKEN'),
        'facebook_token' => env('FB_MESSENGER_TOKEN'),
        'facebook_app_secret' => env('FACEBOOK_CLIENT_SECRET'), // Optional - this is used to verify incoming API calls,
    ],
];
