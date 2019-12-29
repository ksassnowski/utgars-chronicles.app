<?php declare(strict_types=1);

namespace App;

interface Movable
{
    public function move(int $position): void;
}
