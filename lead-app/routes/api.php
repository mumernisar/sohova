<?php

use App\Http\Controllers\Api\LeadController;
use Illuminate\Support\Facades\Route;

Route::middleware('lead.token')
    ->post('/leads', [LeadController::class, 'store']);
