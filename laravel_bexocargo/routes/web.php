<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/shipping-terms', [PageController::class, 'shippingTerms'])->name('shipping-terms');

// Contact form
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

// Shipment booking
Route::get('/book', [ShipmentController::class, 'create'])->name('shipments.create');
Route::post('/book', [ShipmentController::class, 'store'])->name('shipments.store');
Route::get('/booking-confirmation/{tracking_number}', [ShipmentController::class, 'confirmation'])->name('shipments.confirmation');

// Shipment tracking
Route::get('/track', [ShipmentController::class, 'trackingForm'])->name('shipments.track-form');
Route::post('/track', [ShipmentController::class, 'track'])->name('shipments.track');
Route::get('/tracking-result/{tracking_number}', [ShipmentController::class, 'trackingResult'])->name('shipments.tracking-result');

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes (protected)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Shipment management
    Route::get('/shipments', [AdminController::class, 'shipments'])->name('shipments');
    Route::get('/shipments/{tracking_number}', [AdminController::class, 'showShipment'])->name('shipments.show');
    Route::get('/shipments/{tracking_number}/edit', [AdminController::class, 'editShipment'])->name('shipments.edit');
    Route::put('/shipments/{tracking_number}', [AdminController::class, 'updateShipment'])->name('shipments.update');
    Route::delete('/shipments/{tracking_number}', [AdminController::class, 'destroyShipment'])->name('shipments.destroy');
    Route::post('/shipments/{tracking_number}/tracking', [AdminController::class, 'addTrackingUpdate'])->name('shipments.tracking.add');
    
    // Contact form submissions
    Route::get('/contacts', [AdminController::class, 'contacts'])->name('contacts');
    Route::get('/contacts/{id}', [AdminController::class, 'showContact'])->name('contacts.show');
});