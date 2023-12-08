<?php

use App\Mail\OrderMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:send', function () {
    // Generate order data
    $orderData = [
        'id' => fake()->numberBetween(10000, 50000),
        'address' => fake()->address(),
        'recipient' => fake()->name(),
        'total' => fake()->numberBetween(100, 5000),
    ];
    // Send mail
    Mail::send(new OrderMail($orderData));
});
