<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'prefix' => $this->prefix,
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'home_id' => $this->home_id,
            'mu' => $this->mu,
            'tambon' => $this->tambon,
            'amphure' => $this->amphure,
            'city' => $this->city,
            'zip_id' => $this->zip_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
