<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Central)
|--------------------------------------------------------------------------
|
| Rotas do domínio central (não tenant).
| Rotas de tenant ficam em routes/tenant.php
|
*/

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
