<?php

namespace App\Domain\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'password'   => $this->password
        ];
    }
}
