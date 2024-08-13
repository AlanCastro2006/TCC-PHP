<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

//Rotas Tela Inicial - Home
Route::get('/', [CardController::class, 'showHomePage']);
Route::get('/cards/{id}/details', [CardController::class, 'show'])->name('cards.details');

//Rotas para o envio de email
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

//Rotas do método login e logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Rotas protegidas por autenticação
Route::middleware('auth:adm')->group(function () {
    Route::get('/adm/list', [CardController::class, 'index'])->name('adm.list');
    Route::get('/adm/form', [CardController::class, 'create']);
    Route::get('/cards', [CardController::class, 'index']);
    Route::post('/cards/new', [CardController::class, 'store']);
    Route::get('/cards/{id}', [CardController::class, 'edit']);
    Route::put('/cards/{id}', [CardController::class, 'update']);
    Route::delete('/cards/{id}', [CardController::class, 'destroy']);
    Route::put('/cards/{id}/visibility', [CardController::class, 'updateVisibility']);
});




