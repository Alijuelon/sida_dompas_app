<?php
$start = microtime(true);
$request = new \Illuminate\Http\Request();
$controller = new \App\Http\Controllers\LaporanController();
$response = $controller->downloadPdf($request);
$time = microtime(true) - $start;
echo "PDF generated in {$time} seconds. Response status: " . $response->getStatusCode() . "\n";
