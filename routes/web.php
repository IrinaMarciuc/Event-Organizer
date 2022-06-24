<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Home\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('', [HomeController::class, 'home'])->name('home'); 

// Authentication routes
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'checkLogin'])->name('user.login'); 
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'registerUser'])->name('user.register'); 

// Event Routes
Route::get('event/list', [EventController::class, 'viewApprovedEvents'])->name('event.viewAll');
Route::post('event/list', [EventController::class, 'searchApprovedEvents'])->name('event.viewAll.search');
Route::get("event/{id}", [EventController::class, 'viewEvent'])->name('event.show');

// Check authentication routes
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin|moderator|event organiser|user'])->group(function () {
        // Authentication routes
        Route::get('signout', [AuthController::class, 'signOut'])->name('user.signout');
        // Event routes
        Route::post("event/{id}", [EventController::class, 'participateEvent'])->name('event.participate');
        Route::get('participating', [EventController::class, 'viewParticipatingEvents'])->name('event.viewParticipating');
        Route::post('participating', [EventController::class, 'searchParticipatingEvents'])->name('event.searchParticipating');
    });

    Route::middleware(['role:admin|moderator|event organiser'])->group(function () {
        // Event routes
        Route::get('event', [EventController::class, 'getAddEventView'])->name('event');
        Route::post('event', [EventController::class, 'addEvent'])->name('event.add');
        Route::get('created', [EventController::class, 'viewCreatedEvents'])->name('event.viewCreated');
        Route::get('created/{id}', [EventController::class, 'getEditEventView'])->name('event.editView');
        Route::put('update/{id}', [EventController::class, 'editEvent'])->name('event.edit');
        Route::delete('event/{id}', [EventController::class, 'deleteEvent'])->name('event.delete');
        Route::post('created',[EventController::class, 'searchCreatedEvents'])->name('event.created.search');
    });

    Route::middleware(['role:admin|moderator'])->group(function () {
        // Event routes
        Route::get("moderate", [EventController::class, 'showPendingEvents'])->name('event.moderate.showAll');
        Route::post("moderate", [EventController::class, 'searchModerateEvents'])->name('event.moderate.search');
        Route::get("moderate/{id}", [EventController::class, 'moderateEventView'])->name('event.moderate.show');
        Route::post("moderate/{id}", [EventController::class, 'moderateEvent'])->name('event.moderate');
    });

    Route::middleware(['role:admin'])->group(function () {
        // User management routes
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::post('users', [AdminController::class, 'searchUsers'])->name('users.search');
        Route::post('update-role', [AdminController::class, 'updateRole'])->name('user.role.update');
    });
});