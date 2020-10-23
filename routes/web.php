<?php declare(strict_types=1);

use App\Http\Controllers\PaletteController;
use App\Http\Controllers\ProfileController;

Auth::routes();

Route::get('/', 'PageController')->middleware('guest');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController')->name('home');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::post('feedback', 'FeedbackController')->name('feedback.submit');

    Route::post('/user/password', 'ChangePasswordController')->name('password.change');

    Route::get('games/{game}', 'History\ShowGameController')
        ->middleware('can:showGame,game')
        ->name('user.games.show');
    Route::delete('games/{game}', 'History\LeaveGameController')
        ->name('user.games.leave');

    Route::post('histories', 'History\StoreHistoryController')->name('history.store');

    Route::get('histories/{history}/invitation', 'History\AcceptInvitationController')
        ->middleware('signed')
        ->name('invitation.accept');

    Route::delete('histories/{history}/players/{player}', 'History\KickPlayerController')
        ->middleware('can:kickPlayer,history')
         ->name('history.players.kick');

    Route::get('histories/{history}', 'History\ShowHistoryController')
        ->middleware('can:showHistory,history')
        ->name('history.show');

    Route::delete('histories/{history}', 'History\DeleteHistoryController')
        ->middleware('can:deleteHistory,history')
        ->name('history.delete');

    Route::get('histories/{history}/export', 'History\ExportController')
        ->middleware('can:modifyGame,history')
        ->name('history.export');

    Route::get('histories/{history}/sync', 'History\BoardController')
        ->middleware('can:modifyGame,history')
        ->name('history.sync');

    Route::get('histories/{history}/play', 'History\GameController')
        ->middleware('can:modifyGame,history')
        ->name('history.play');

    Route::patch('histories/{history}/seed', 'History\UpdateSeedController')
        ->middleware('can:modifyGame,history')
        ->name('history.update-seed');

    Route::post('histories/{history}/events/{event:id}/scenes', 'Event\CreateSceneController')
        ->middleware('can:createScene,event')
        ->name('events.scenes.store');

    Route::put('histories/{history}/scenes/{scene:id}', 'Scene\UpdateSceneController')
        ->middleware('can:updateScene,scene')
        ->name('scenes.update');

    Route::delete('histories/{history}/scenes/{scene:id}', 'Scene\DeleteSceneController')
        ->middleware('can:deleteScene,scene')
        ->name('scenes.delete');

    Route::post('/histories/{history}/scenes/{scene:id}/move', 'Scene\MoveSceneController')
        ->middleware('can:moveScene,scene')
        ->name('scenes.move');
});

Route::group(['middleware' => 'microscope'], function () {
    Route::post('histories/{history}/palette', [PaletteController::class, 'store'])
        ->name('history.palette.store');

    Route::put('histories/{history}/palette/{palette:id}', [PaletteController::class, 'update'])
        ->name('palette.update');

    Route::delete('histories/{history}/palette/{palette:id}', [PaletteController::class, 'destroy'])
        ->name('palette.delete');

    Route::post('histories/{history}/legacies', 'Legacy\CreateLegacyController')
        ->name('history.legacies.store');

    Route::put('histories/{history}/legacies/{legacy:id}', 'Legacy\UpdateLegacyController')
        ->name('legacies.update');

    Route::delete('histories/{history}/legacies/{legacy:id}', 'Legacy\DeleteLegacyController')
        ->name('legacies.delete');

    Route::post('histories/{history}/focus', 'History\DefineFocusController')
        ->name('history.focus.define');

    Route::put('histories/{history}/focus/{focus:id}', 'Focus\UpdateFocusController')
        ->name('focus.update');

    Route::delete('histories/{history}/focus/{focus:id}', 'Focus\DeleteFocusController')
        ->name('focus.delete');

    Route::post('histories/{history}/periods', 'History\CreatePeriodController')
        ->name('history.periods.store');

    Route::put('histories/{history}/periods/{period:id}', 'Period\UpdatePeriodController')
        ->name('periods.update');

    Route::delete('histories/{history}/periods/{period:id}', 'Period\DeletePeriodController')
        ->name('periods.delete');

    Route::post('histories/{history}/periods/{period:id}/move', 'History\MovePeriodController')
        ->name('periods.move');

    Route::post('histories/{history}/periods/{period:id}/events', 'Period\CreateEventController')
        ->name('periods.events.store');

    Route::put('histories/{history}/events/{event:id}', 'Event\UpdateEventController')
        ->name('events.update');

    Route::post('histories/{history}/events/{event:id}/move', 'Event\MoveEventController')
        ->name('events.move');

    Route::delete('histories/{history}/events/{event:id}', 'Event\DeleteEventController')
        ->name('events.delete');
});
