<?php declare(strict_types=1);

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController')->name('home');

    Route::post('histories', 'History\StoreHistoryController')->name('history.store');
    Route::get('histories/create', 'History\CreateHistoryController')->name('history.create');

    Route::get('histories/{history}', 'History\GameController')
        ->middleware('can:modifyGame,history')
        ->name('history.play');

    Route::put('histories/{history}', 'History\UpdateHistoryController')
        ->middleware('can:updateHistory,history')
        ->name('history.update');

    Route::post('histories/{history}/palette', [\App\Http\Controllers\PaletteController::class, 'store'])
        ->middleware('can:modifyGame,history')
        ->name('history.palette.store');

    Route::put('palette/{palette}', [\App\Http\Controllers\PaletteController::class, 'update'])
        ->middleware('can:updatePalette,palette')
        ->name('palette.update');

    Route::delete('palette/{palette}', [\App\Http\Controllers\PaletteController::class, 'destroy'])
        ->middleware('can:deletePalette,palette')
        ->name('palette.delete');

    Route::post('histories/{history}/focus', 'History\DefineFocusController')
        ->middleware('can:modifyGame,history')
        ->name('history.focus.define');

    Route::put('focus/{focus}', 'Focus\UpdateFocusController')
        ->middleware('can:editFocus,focus')
        ->name('focus.update');

    Route::delete('focus/{focus}', 'Focus\DeleteFocusController')
        ->middleware('can:deleteFocus,focus')
        ->name('focus.delete');

    Route::post('histories/{history}/periods', 'History\CreatePeriodController')
        ->middleware('can:modifyGame,history')
        ->name('history.periods.store');

    Route::put('periods/{period}', 'Period\UpdatePeriodController')
        ->middleware('can:updatePeriod,period')
        ->name('periods.update');

    Route::delete('periods/{period}', 'Period\DeletePeriodController')
        ->middleware('can:deletePeriod,period')
        ->name('periods.delete');

    Route::post('histories/{history}/periods/{period}/move', 'History\MovePeriodController')
        ->middleware('can:modifyGame,history')
        ->name('history.periods.move');

    Route::post('periods/{period}/events', 'Period\CreateEventController')
        ->middleware('can:createEvent,period')
        ->name('periods.events.store');

    Route::put('events/{event}', 'Event\UpdateEventController')
        ->middleware('can:updateEvent,event')
        ->name('events.update');

    Route::post('events/{event}/move', 'Event\MoveEventController')
        ->middleware('can:moveEvent,event')
        ->name('events.move');

    Route::delete('events/{event}', 'Event\DeleteEventController')
        ->middleware('can:deleteEvent,event')
        ->name('events.delete');

    Route::post('events/{event}/scenes', 'Event\CreateSceneController')
        ->middleware('can:createScene,event')
        ->name('events.scenes.store');

    Route::put('scenes/{scene}', 'Scene\UpdateSceneController')
        ->middleware('can:updateScene,scene')
        ->name('scenes.update');

    Route::delete('scenes/{scene}', 'Scene\DeleteSceneController')
        ->middleware('can:deleteScene,scene')
        ->name('scenes.delete');

    Route::post('scenes/{scene}/move', 'Scene\MoveSceneController')
        ->middleware('can:moveScene,scene')
        ->name('scenes.move');
});
