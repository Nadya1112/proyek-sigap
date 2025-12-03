<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Api\PetaController;

$controller = new PetaController();
$response = $controller->kelurahan();
if (is_object($response) && method_exists($response, 'getContent')) {
    echo $response->getContent();
} else {
    echo json_encode($response);
}
