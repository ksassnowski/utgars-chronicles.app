<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Profile/Profile');
    }
}
