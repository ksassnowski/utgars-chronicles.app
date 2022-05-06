<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;

final class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => bcrypt($request->password()),
        ]);

        return back()->with('success', __('Password successfully changed'));
    }
}
