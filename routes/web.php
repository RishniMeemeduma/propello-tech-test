<?php

use App\Http\Controllers\AssignTagsToTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'verified'])
    ->name('tasks.')
    ->controller(TaskController::class)
    ->group(function() {
        Route::get('', 'index')->name('home');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('edit/{task}', 'edit')->name('edit');
        Route::post('edit/{task}', 'update')->name('update');
        Route::get('delete/{task}', 'destroy')->name('destroy');
        Route::get('complete/{task}', 'complete')->name('complete');
        Route::get('assign-tags/{task}', 'assignTags')->name('assignTags');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('tags')
    ->name('tags.')
    ->controller(TagController::class)
    ->group(function() {
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('edit/{tag}', 'edit')->name('edit');
        Route::post('edit/{tag}', 'update')->name('update');
        Route::get('delete/{tag}', 'destroy')->name('destroy');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('task-tags')
    ->name('task-tags.')
    ->controller(AssignTagsToTaskController::class)
    ->group(function() {
        Route::post('assign-tags', 'store')->name('store');
        Route::get('delete/{task}/{tag}', 'destroy')->name('delete');
    });

Route::middleware('auth')
    ->prefix('profile')
    ->name('profile.')
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('', 'edit')->name('edit');
        Route::patch('', 'update')->name('update');
        Route::delete('', 'destroy')->name('destroy');
    });

require __DIR__.'/auth.php';
