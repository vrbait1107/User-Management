<?php

namespace App\BusinessObjects;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserBO
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}
