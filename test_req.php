<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$req = new \Illuminate\Http\Request();
$req->setMethod('POST');
$req->merge(['method' => 'recovery']);

echo "Property: " . $req->method . "\n";
echo "Method call: " . $req->method() . "\n";
echo "Input: " . $req->input('method') . "\n";
