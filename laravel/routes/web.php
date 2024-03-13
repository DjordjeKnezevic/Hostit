<?php

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MailingListController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.index');
})->name('index');

Route::get('/price', function () {
    return view('pages.price');
})->name('price');

Route::get('/service', function () {
    return view('pages.service');
})->name('service');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/about', function () {
    $locations = Location::all();
    return view('pages.about', compact('locations'));
})->name('about');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('showLogin');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('showRegister');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware(['auth', 'verified'])->post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/')->with('status', 'Email verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('resent', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/servers', [ServerController::class, 'index'])->name('server');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rent-server', [ServerController::class, 'rent'])->name('rent-server');
    Route::get('/get-servers', [ServerController::class, 'getServers'])->name('get-servers');
    Route::get('/get-server-pricing', [ServerController::class, 'getServerPricing'])->name('get-server-pricing');
    Route::get('/get-locations', [ServerController::class, 'getLocations'])->name('get-locations');
    Route::get('/location-details/{location}', [ServerController::class, 'locationDetails'])->name('location-details');
    Route::get('/server-details/{server}', [ServerController::class, 'serverDetails'])->name('server-details');
    Route::get('/pricing-details/{pricing}', [ServerController::class, 'pricingDetails'])->name('pricing-details');
});

Route::middleware('auth')->group(function () {
    Route::middleware('auth')->get('/profile', [ProfileController::class, 'index'])->name('profile');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/process-renting', [ProfileController::class, 'processRenting'])->name('process-renting');
    Route::get('/user/servers', [ProfileController::class, 'showRentedServers'])->name('user-servers');
    Route::get('/user/servers/filter', [ProfileController::class, 'filterRentedServers'])->name('filter-servers');
    Route::put('/user/servers/server-stop/{server}', [ProfileController::class, 'stopServer'])->name('server-stop');
    Route::put('/user/servers/server-start/{server}', [ProfileController::class, 'startServer'])->name('server-start');
    Route::put('/user/servers/server-restart/{server}', [ProfileController::class, 'restartServer'])->name('server-restart');
    Route::post('/user/servers/server-terminate/{server}', [ProfileController::class, 'terminateServer'])->name('server-terminate');
});

Route::post('/subscribe', [MailingListController::class, 'subscribe'])->name('subscribe');
