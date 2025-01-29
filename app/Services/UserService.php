<?php

namespace App\Services;

use App\BusinessObjects\UserBO;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $userBO;

    public function __construct(UserBO $userBO)
    {
        $this->userBO = $userBO;
    }

    public function index(int $perPage = 15, int $currentPage = 1)
    {

        $currentPage = !empty(request()->page) ? request()->page : $currentPage;
        $perPage = !empty(request()->per_page) ? request()->per_page : $perPage;

        $cacheKey = "users_page_{$perPage}_page_{$currentPage}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $users = User::paginate($perPage);

        $userIds = $users?->pluck('id')?->toArray();

        Cache::tags($userIds)->put($cacheKey, $users, now()->addMinutes(10));

        return $users;
    }

    public function create(array $data)
    {

        $user = $this->userBO->create($data);

        $cacheKey = 'user_' . $user->id;

        Cache::put($cacheKey, $user, now()->addMinutes(10));

        return $user;
    }

    public function update(User $user, array $data)
    {
        Cache::forget("user_{$user->id}");
        Cache::tags($user->id)->flush();

        return $this->userBO->update($user, $data);
    }
}