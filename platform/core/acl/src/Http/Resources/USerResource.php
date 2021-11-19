<?php

namespace Botble\ACL\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
        $data = [
            'id'        => $this->id,
            'username'  => $this->username,
            'name'      => $this->getFullName(),
            'firstName' => $this->first_name,
            'lastName'  => $this->last_name,
            'email'     => $this->email,
            'avatar'    => $this->avatar_url,
            'isSuper'   => $this->when($this->isSuperUser(), $this->super_user),
        ];

        if ($this->hasCompany()) {
            $data += ['company' => new CompanyResource($this->company)];
        }

        if ($this->hasRoles()) {
            $data += ['roles'   => RoleResource::collection($this->roles)];
        }

        return $data;
    }
}
