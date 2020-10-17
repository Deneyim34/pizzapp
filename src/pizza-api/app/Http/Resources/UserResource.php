<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserResource extends JsonResource
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

        $district = User::find($this->id)->District->name;
        $city = User::find($this->id)->City->name;
        $country = User::find($this->id)->Country->name;

        return [
            "id" => $this->id,
            "name" => $this->name,
            "surname" => $this->surname,
            "email" => $this->email,
            "phone" => $this->phone,
            "address" => $this->address,
            "district_id" => $this->district_id,
            "city_id" => $this->city_id,
            "country_id" => $this->country_id,
            "district" => $district,
            "city" => $city,
            "country" => $country,
            "role" => $this->role
        ];

    }
}
