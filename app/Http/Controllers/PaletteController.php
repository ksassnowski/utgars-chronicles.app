<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Controllers;

use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemDeleted;
use App\Events\PaletteItemUpdated;
use App\History;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;
use App\Palette;
use Illuminate\Http\RedirectResponse;

class PaletteController extends Controller
{
    public function store(CreatePaletteItemRequest $request, History $history): RedirectResponse
    {
        $palette = $history->addToPalette($request->name(), $request->type());

        broadcast(new ItemAddedToPalette($palette))->toOthers();

        return redirect()->back();
    }

    public function update(UpdatePaletteItemRequest $request, History $history, Palette $palette): RedirectResponse
    {
        $palette->update($request->validated());

        broadcast(new PaletteItemUpdated($palette))->toOthers();

        return redirect()->back();
    }

    public function destroy(History $history, Palette $palette): RedirectResponse
    {
        $palette->delete();

        broadcast(new PaletteItemDeleted($palette, $palette->history))->toOthers();

        return redirect()->back();
    }
}
