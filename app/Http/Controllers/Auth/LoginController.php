<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUser;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Implements showLoginForm().
     *
     * This method returns a view for the user to login
     *
     * @return view
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Implements login.
     *
     * Description
     *
     * @param $request Form request for validating the request data
     *
     * @return JsonResponse|RedirectResponse Response format
     */
    public function login(LoginUser $request): JsonResponse
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('browser')->plainTextToken;

            return response()
                ->json(
                    [
                        'status' => true,
                        'statusCode' => 200,
                        'data' => [
                            'access_token' => $token,
                        ],
                    ],
                    200,
                );
        }

        return response()
            ->json(
                [
                    'status' => false,
                    'statusCode' => 401,
                    'message' => 'Unauthorized.'
                ],
                401,
            );
    }

    /**
     * Implements logout.
     *
     * Description
     *
     * @return JsonResponse JSON response format
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(null, 204);
    }
}
