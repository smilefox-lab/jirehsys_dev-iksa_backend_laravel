<?php

namespace Botble\ACL\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Company
 */
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id ?? null,
            'name' => $this->name ?? null,
            'files'  => $this->files ?? []
        ];
    }
}
