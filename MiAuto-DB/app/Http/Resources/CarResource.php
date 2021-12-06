<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'vin_number' => $this->vin_number,
            'plate' => $this->plate,
            'type' => $this->type,
            'fuel' => $this->fuel,
            'make' => $this->make,
            'model' => $this->model,
            'engine' => $this->engine,
            'gear_box' => $this->gear_box,
            'air_conditioner' => $this->air_conditioner,
            'color'=> $this->color,
        ];
    }
}
