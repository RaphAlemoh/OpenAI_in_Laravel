<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;

Route::get('/', function () {
    return view('welcome');
});

// This method uses the open ai package
Route::get('/openai', [OpenAIController::class, 'open_ai']);


// This method uses the http method to access OpenAI API
Route::get('/openai/http', [OpenAIController::class, 'open_ai_http']);


// This method uses the http method to access OpenAI API making request to codex models
Route::get('/openai/codex', [OpenAIController::class, 'open_ai_http_codex']);
