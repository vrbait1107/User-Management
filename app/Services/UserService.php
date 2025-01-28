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

    public function index(int $perPage = 15)
    {
        $cacheKey = 'users_all_page_' . $perPage;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $users = User::paginate($perPage);

        Cache::put($cacheKey, $users, now()->addMinutes(10));

        return $users;
    }

    public function create(array $data)
    {
        $cacheKey = 'user_' . $data['email'];
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $user = $this->userBO->create($data);
        Cache::put($cacheKey, $user, now()->addMinutes(10));
        return $user;
    }

    public function update(User $user, array $data)
    {
        Cache::forget('user_' . $user->email);
        return $this->userBO->update($user, $data);
    }

    public function get($id)
    {
        $cacheKey = 'user_' . $id;

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            return User::findOrFail($id);
        });
    }
}
