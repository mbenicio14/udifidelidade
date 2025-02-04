<?php

  /*
   |--------------------------------------------------------------------------
   | Defaults
   |--------------------------------------------------------------------------
   |
   | The values below are defaults for the app.
   | Can be overridden in the .env file.
   |
   */

  return [
      /*
      |--------------------------------------------------------------------------
      | Force SSL
      |--------------------------------------------------------------------------
      */

      'force_ssl' => env('FORCE_SSL', false),

      /*
      |--------------------------------------------------------------------------
      | SEO
      |--------------------------------------------------------------------------
      */

      'page_title_delimiter' => env('PAGE_TITLE_DELIMITER', ' - '),

      /*
       |--------------------------------------------------------------------------
       | App
       |--------------------------------------------------------------------------
       */

      'app_name' => env('APP_NAME', 'Reward Loyalty'),
      'app_logo' => env('APP_LOGO', ''),
      'app_logo_dark' => env('APP_LOGO_DARK', ''),
      'app_url' => env('APP_URL', 'https://localhost'),
      'app_is_installed' => env('APP_IS_INSTALLED', false),
      'app_admin_email' => env('APP_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS', 'admin@example.com')),
      'app_demo' => env('APP_DEMO', false),
      'cookie_consent' => env('APP_COOKIE_CONSENT', false),

      /*
       |--------------------------------------------------------------------------
       | E-mail
       |--------------------------------------------------------------------------
       */

      'registration_email_link' => env('APP_REGISTRATION_EMAIL_LINK', true),
      'mail_from_name' => env('MAIL_FROM_NAME', 'Reward Loyalty'),
      'mail_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),

      /*
       |--------------------------------------------------------------------------
       | Localization
       |--------------------------------------------------------------------------
       */

      'time_zone' => env('DEFAULT_TIMEZONE', 'America/Los_Angeles'),
      'currency' => env('DEFAULT_CURRENCY', 'USD'),

      /*
       |--------------------------------------------------------------------------
       | Code to Redeem Points Valid Minutes
       |--------------------------------------------------------------------------
       | Determines how long a generated code is valid (in minutes).
       | Example: 60 means the code is valid for 1 hour. 60 * 24 is 1 day.
       */
      'code_to_redeem_points_valid_minutes' => env('CODE_TO_REDEEM_POINTS_VALID_MINUTES', (60 * 24) * 3),

      /*
       |--------------------------------------------------------------------------
       | Number of days that a staff member can see a member he/she interacted with
       |--------------------------------------------------------------------------
       */

      'staff_transaction_days_ago' => env('APP_STAFF_TRANSACTION_DAYS_AGO', 7),
  ];
