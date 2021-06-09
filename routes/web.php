<?php declare(strict_types=1);

use App\Http\Controllers\PaletteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LfgRequestController;
use App\Http\Controllers\LookingForGroupController;
use App\Http\Controllers\History\GuestInvitationController;

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

    Route::post('histories', 'History\StoreHistoryController')
        ->name('history.store');
    Route::patch('histories/{history}/visibility', 'History\ChangeVisibilityController')
        ->middleware('can:updateVisibility,history')
        ->name('history.visibility');

    Route::delete('histories/{history}/players/{player}', 'History\KickPlayerController')
        ->middleware('can:kickPlayer,history')
         ->name('history.players.kick');

    Route::get('histories/{history}', 'History\ShowHistoryController')
        ->middleware('can:showHistory,history')
        ->name('history.show');

    Route::delete('histories/{history}', 'History\DeleteHistoryController')
        ->middleware('can:deleteHistory,history')
        ->name('history.delete');

    Route::get('/lfg', [LookingForGroupController::class, 'index'])
        ->name('lfg.index');
    Route::get('/lfg/create', [LookingForGroupController::class, 'create'])
        ->name('lfg.create');
    Route::get('/lfg/{lfg}', [LookingForGroupController::class, 'show'])
        ->name('lfg.show');
    Route::post('/lfg', [LookingForGroupController::class, 'store'])
        ->name('lfg.store');

    Route::get('/lfg/requests', [LfgRequestController::class, 'index'])
        ->name('lfg.requests.index');
    Route::post('/lfg/{lfg}/requests', [LfgRequestController::class, 'store'])
        ->name('lfg.requests.store');
    Route::post('/requests/{request}/accept', [LfgRequestController::class, 'accept'])
        ->middleware('can:accept,request')
        ->name('lfg.requests.accept');
    Route::post('/requests/{request}/reject', [LfgRequestController::class, 'reject'])
        ->middleware('can:reject,request')
        ->name('lfg.requests.reject');
});

Route::group(['middleware' => 'auth:microscope'], function () {
    Route::get('histories/{history}/invitation', 'History\AcceptInvitationController')
        ->middleware('signed')
        ->name('invitation.accept');

    Route::get('histories/{history}/invitation/guest', [GuestInvitationController::class, 'showForm'])
        ->middleware('signed')
        ->name('invitation.accept.show-form');

    Route::post('histories/{history}/invitation/guest', [GuestInvitationController::class, 'accept'])
        ->middleware('signed')
        ->name('invitation.accept.guest');
});

Route::group(['middleware' => 'microscope'], function () {
    Route::get('histories/{history}/export', 'History\ExportController')
        ->name('history.export');

    Route::get('histories/{history}/sync', 'History\BoardController')
        ->name('history.sync');

    Route::get('histories/{history}/play', 'History\GameController')
        ->name('history.play');

    Route::patch('histories/{history}/seed', 'History\UpdateSeedController')
        ->name('history.update-seed');

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

    Route::post('histories/{history}/events/{event:id}/scenes', 'Event\CreateSceneController')
        ->name('events.scenes.store');

    Route::put('histories/{history}/scenes/{scene:id}', 'Scene\UpdateSceneController')
        ->name('scenes.update');

    Route::delete('histories/{history}/scenes/{scene:id}', 'Scene\DeleteSceneController')
        ->name('scenes.delete');

    Route::post('/histories/{history}/scenes/{scene:id}/move', 'Scene\MoveSceneController')
        ->name('scenes.move');
});
