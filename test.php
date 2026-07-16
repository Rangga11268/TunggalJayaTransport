<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$scheds = \App\Models\Schedule::with('bus')->get();
$res = $scheds->map(function($s) {
    return [
        'id' => $s->id,
        'time' => $s->departure_time->format('H:i:s'),
        'is_departed' => $s->hasDeparted(null)
    ];
});
echo json_encode($res, JSON_PRETTY_PRINT);
