<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'period' => $this->period->id,
            'position' => $this->position,
            'name' => $this->name,
            'type' => $this->type,
            'scenes' => Scene::collection($this->scenes),
        ];
    }
}
