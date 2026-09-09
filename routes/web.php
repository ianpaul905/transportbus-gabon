<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControleurController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/recherche', [HomeController::class, 'rechercher'])->name('recherche');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Réservation
Route::get('/reserver', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservation.store');

// Paiement
Route::name('payment.')
    ->group(function () {
        Route::get('/paiement/{reservation}', [PaymentController::class, 'form'])->name('form');
        Route::post('/paiement/{reservation}/initier', [PaymentController::class, 'initier'])->name('initier');
        Route::post('/paiement/{reservation}/confirmer', [PaymentController::class, 'confirmer'])->name('confirmer');
        Route::get('/ebillet/{reservation}', [PaymentController::class, 'eBillet'])->name('ebillet');
    });

// Espace administrateur
Route::middleware(['auth', 'role:admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/trajets', [AdminController::class, 'trajets'])->name('trajets');
    Route::post('/trajets', [AdminController::class, 'trajetStore'])->name('trajets.store');
    Route::delete('/trajets/{trip}', [AdminController::class, 'trajetDestroy'])->name('trajets.destroy');
    Route::get('/bus', [AdminController::class, 'bus'])->name('bus');
    Route::post('/bus', [AdminController::class, 'busStore'])->name('bus.store');
    Route::patch('/bus/{bus}', [AdminController::class, 'busUpdate'])->name('bus.update');
    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
    Route::get('/chiffre-affaires', [AdminController::class, 'chiffreAffaires'])->name('chiffre-affaires');
});

// Espace contrôleur
Route::middleware(['auth', 'role:controleur'])->name('controleur.')->prefix('controleur')->group(function () {
    Route::get('/', [ControleurController::class, 'dashboard'])->name('dashboard');
    Route::get('/scanner', [ControleurController::class, 'scanner'])->name('scanner');
    Route::post('/verifier', [ControleurController::class, 'verifier'])->name('verifier');
    Route::post('/embarquer/{reservation}', [ControleurController::class, 'validerEmbarquement'])->name('embarquer');
});