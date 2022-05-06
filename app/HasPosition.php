<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $position
 */
trait HasPosition
{
    public function move(int $position): void
    {
        if ($this->position === $position) {
            return;
        }

        if ($position > $this->position) {
            $startPosition = $this->position;
            $endPosition = $position;
            $updateStmt = 'position - 1';
        } else {
            $startPosition = $position;
            $endPosition = $this->position;
            $updateStmt = 'position + 1';
        }

        $this->position = $position;

        $query = self::query();
        $this->limitElementsToMove($query);

        $query->whereBetween('position', [$startPosition, $endPosition])
            ->where('id', '!=', $this->id)
            ->update([
                'position' => DB::raw($updateStmt),
            ]);

        $this->save();
    }

    abstract protected function limitElementsToMove(Builder $query): void;
}
