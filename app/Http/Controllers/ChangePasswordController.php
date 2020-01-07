<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request)
    {
        $request->user()->update([
            'password' => bcrypt($request->password()),
        ]);

        return back()->with('success', __('Password successfully changed'));
    }
}
