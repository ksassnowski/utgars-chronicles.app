<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\History;
use App\Palette;
use Illuminate\Http\JsonResponse;
use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemDeleted;
use App\Events\PaletteItemUpdated;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;

class PaletteController extends Controller
{
    public function store(CreatePaletteItemRequest $request, History $history): JsonResponse
    {
        $palette = $history->addToPalette($request->name(), $request->type());

        broadcast(new ItemAddedToPalette($palette));

        return response()->json([], 201);
    }

    public function update(UpdatePaletteItemRequest $request, Palette $palette): JsonResponse
    {
        $palette->update($request->validated());

        broadcast(new PaletteItemUpdated($palette));

        return response()->json();
    }

    public function destroy(Palette $palette): JsonResponse
    {
        $palette->delete();

        broadcast(new PaletteItemDeleted($palette->id, $palette->history));

        return response()->json([], 204);
    }
}
