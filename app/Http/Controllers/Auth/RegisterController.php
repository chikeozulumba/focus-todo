<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Implements showRegistrationForm().
     *
     * This method returns a view for the user to register
     *
     * @return view
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Implements register.
     *
     * Description
     *
     * @param $request Form request for validating the request data
     *
     * @return JsonResponse An array of menu items
     */
    public function register(RegisterUser $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['password'] = Hash::make($payload['password']);
        User::create($payload);
        return response()->json(
            [
                "status" => true,
                "statusCode" => 201
            ],
            201,
        );
    }
}
