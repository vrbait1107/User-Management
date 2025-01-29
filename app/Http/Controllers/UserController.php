<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        try {
            $user = $this->userService->index();
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {

            $user = $this->userService->create($request->validated());

            return response()->json([
                "success" => true,
                "data" => $user
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {

            $updatedUser = $this->userService->update($user, $request->validated());

            return response()->json(
                [
                    "success" => true,
                    "data" => $updatedUser
                ],
                200
            );
        } catch (\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }

    public function show(User $user)
    {
        try {

            /*  
            * Not Using Service Layer and Business Object Layer for Simplicity
            * Using Both Layer here creating complex code
            */

            $cacheKey = "user_{$user->id}";

            $user = Cache::remember($cacheKey, now()->addMinutes(10), fn () => $user);

            return response()->json([
                "success" => true,
                "data" => $user
            ]);

        } catch (ModelNotFoundException $e) {

            return response()->json([
                "success" => false,
                "message" => "User not found"
            ], 404);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {

            /*  
            * Not Using Service Layer and Business Object Layer for Simplicity
            * Using Both Layer here creating complex code
            */

            Cache::forget("user_{$user->id}");
            Cache::tags($user->id)->flush();

            $user->delete();

            return response()->json([
                "success" => true,
                "message" => "User deleted successfully"
            ]);

        } catch (ModelNotFoundException $e) {

            return response()->json([
                "success" => false,
                "message" => "User not found"
            ], 404);
        }
    }
}
