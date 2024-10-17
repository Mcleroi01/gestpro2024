<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CandidatController as ApiCandidatController;

Route::apiResource('candidat', ApiCandidatController::class);
Route::post('candidat/attributes', [ApiCandidatController::class, 'storeAttributes']);
Route::post('candidat/show', [ApiCandidatController::class, 'showDetail']);
