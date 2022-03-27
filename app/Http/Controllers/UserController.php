<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** @todo test */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create(
            name: $request->name,
            email: $request->email,
            password: Hash::make($request->password),
        )
        ->getUser();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->name,
        ]);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(): Response
    {
        auth()->user()->delete();

        return response()->noContent();
    }

    public function sendEmailVerification()
    {
        $this->userService
            ->setUser(auth()->user())
            ->sendEmailVerification();
    }

    /**
     * @throws Exception
     */
    public function checkVerificationCode(Request $request): JsonResponse
    {
        $userVerified = $this->userService
            ->setUser(auth()->user())
            ->checkVerificationCode($request->verification_code)
            ->verifyEmail()
            ->getUser();

        return response()->json($userVerified);
    }
}
