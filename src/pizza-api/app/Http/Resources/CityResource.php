<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

    }

    public function toData()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "country_id" => $this->country_id
        ];
    }
}
