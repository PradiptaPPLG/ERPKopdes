<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $mail = new \App\Mail\SendOtpMail('123456', 'Test User', 'forgot_password');
    Illuminate\Support\Facades\Mail::to('pradipta02032009@gmail.com')->send($mail);
    echo "MAIL SENT SUCCESSFULLY\n";
} catch (\Exception $e) {
    echo "MAIL EXCEPTION: " . $e->getMessage() . "\n";
}

