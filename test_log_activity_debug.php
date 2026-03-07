<?php

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Traits\LogsActivity;

class Test {
    use LogsActivity;

    public function test() {
        return $this->logActivity(null, 'seguridad', 'login_fallido', 'Autenticación', 'Intento de inicio de sesión fallido');
    }
}

$test = new Test();
$result = $test->test();
var_dump($result);