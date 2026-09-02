<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = \App\Models\User::first();
    echo "Sending OTP to: " . $user->email . "\n";
    $otp = '123456';
    Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp, $user->name, 'forgot_password'));
    echo "MAIL SENT SUCCESSFULLY TO " . $user->email . "\n";
} catch (\Exception $e) {
    echo "MAIL EXCEPTION: " . $e->getMessage() . "\n";
}
