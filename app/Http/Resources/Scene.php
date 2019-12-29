<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Scene extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'scene' => $this->scene,
            'answer' => $this->answer,
            'type' => $this->type,
            'position' => $this->position,
        ];
    }
}
