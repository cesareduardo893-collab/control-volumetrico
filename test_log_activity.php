<?php

require_once 'vendor/autoload.php';

use App\Http\Controllers\Traits\LogsActivity;

class Test {
    use LogsActivity;
}

$test = new Test();
$result = $test->logActivity(1, 'test', 'administracion_sistema', 'test', 'test description');
var_dump($result);