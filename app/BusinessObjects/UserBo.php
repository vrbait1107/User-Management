<?php
namespace App\BusinessObjects;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserBO
{
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }
}
