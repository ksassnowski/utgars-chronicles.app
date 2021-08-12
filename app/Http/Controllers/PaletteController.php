<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\History;
use App\Palette;
use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemDeleted;
use App\Events\PaletteItemUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;

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
