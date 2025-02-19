<?php

use Illuminate\Support\Facades\Route;
use Webbundels\Essentials\Http\Controllers\CommitController;
use Webbundels\Essentials\Http\Controllers\ChangelogController;
use Webbundels\Essentials\Http\Controllers\SubchapterController;
use Webbundels\Essentials\Http\Controllers\DocumentationController;

Route::controller(ChangelogController::class)
->name('changelog.')
->middleware(['web', 'auth.basic'])
->prefix('changelog')
->group(function()
{
    # Index
    Route::get('/',             'index')->name('index');

    # Create
    Route::get('create',        'create')->name('create');
    Route::post('create',       'store')->name('store');

    # Edit
    Route::get('{id}',          'edit')->name('edit');
    Route::post('{id}',         'update')->name('update');

    # Deletes
    Route::get('{id}/delete',   'delete')->name('delete');
});


Route::middleware(['web', 'auth.basic'])->group(function() {
    Route::controller(CommitController::class)
    ->name('commit.')
    ->prefix('commit')
    ->group(function() {
        Route::get('/', 'get')->name('get');
        Route::get('refresh', 'refresh')->name('refresh');
    });

    Route::controller(SubchapterController::class)
    ->name('subchapter.')
    ->prefix('subchapter')
    ->group(function() {

        Route::get('{id}',          'edit')->name('edit');
        Route::post('{id}',         'update')->name('update');

        Route::post('change-order/{id}', 'changeOrder')->name('change_order');


        Route::get('{id}/delete',   'delete')->name('delete');
    });

});


Route::controller(DocumentationController::class)
->name('documentation.')
->middleware(['web', 'auth.basic'])
->prefix('documentatie')
->group(function()
{
    # Index
    Route::get('/',             'index')->name('index');

    # Create
    Route::get('create',        'create')->name('create');
    Route::post('create',       'store')->name('store');

    # Edit
    Route::post('change-order', 'changeOrder')->name('change_order');
    Route::get('{id}',          'edit')->name('edit');
    Route::post('{id}',         'update')->name('update');

    # Deletes
    Route::get('{id}/delete',   'delete')->name('delete');
});
