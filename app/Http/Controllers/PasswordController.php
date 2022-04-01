<?php

namespace App\Http\Controllers;

use App\Models\Password;
use App\Services\PasswordService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class PasswordController extends Controller
{
    /** @todo test */
    public function __construct(private PasswordService $passwordService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $passwords = QueryBuilder::for(Password::class)
            ->allowedFilters('url')
            ->defaultSort('-created_at')
            ->get();

        return response()->json($passwords);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $password = $this->passwordService
            ->create(
                url: $request->url,
                password: $request->password,
            )
            ->getPassword();

        return response()->json($password);
    }

    /**
     * Display the specified resource.
     *
     * @param Password $password
     * @return JsonResponse
     */
    public function show(Password $password): JsonResponse
    {
        return response()->json($password);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Password $password
     * @return JsonResponse
     */
    public function update(Request $request, Password $password): JsonResponse
    {
        $password->update([
            'url' => $request->url,
            'password' => $this->passwordService->encrypt($request->password),
        ]);

        return response()->json($password);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Password $password
     * @return Response
     */
    public function destroy(Password $password): Response
    {
        $password->delete();

        return response()->noContent();
    }

    /**
     * @param Request $request
     * @param Password $password
     * @return JsonResponse
     * @throws Exception
     */
    public function showDecryptedPassword(Request $request, Password $password): JsonResponse
    {
        if (! $password->user->isVerified()) {
            throw new Exception('Apenas usuários verificados podem utilizar esse recurso.', 400);
        }

        $password = $this->passwordService
            ->setPassword($password)
            ->decrypt();

        return response()->json($password);
    }
}
