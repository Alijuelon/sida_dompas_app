<?php
$request = request();
$request->setRouteResolver(function() {
    $route = new \Illuminate\Routing\Route('PUT', 'keluarga/{keluarga}', []);
    $route->bind(request());
    $route->setParameter('keluarga', \App\Models\Keluarga::first());
    return $route;
});
$id = $request->route('keluarga')?->id;
echo "ID: " . $id . "\n";
