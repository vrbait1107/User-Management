<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserRequest $request): JsonResponse
    {
        $user = $this->userService->index();
        return response()->json($user);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        return response()->json($user, 201);
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->update($user, $request->validated());
        return response()->json($updatedUser, 200);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->get($id);
        return response()->json($user);
    }
}
