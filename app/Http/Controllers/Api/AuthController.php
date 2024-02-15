<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function create(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), [
                'role' => 'required',
                'company_id' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validateUser->errors(),
                    ],
                    401,
                );
            }

            $user = User::create([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->syncRoles($request->role);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken('API TOKEN')->plainTextToken,
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validateUser->errors(),
                    ],
                    401,
                );
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                // throw ValidationException::withMessages([
                //     'email' => 'required',
                // ]);

                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Email & Password does not match with our record.',
                    ],
                    401,
                );
            }

            $user = User::where('email', $request->email)->first();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken('API TOKEN')->plainTextToken,
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function user()
    {


        $user = User::with('roles', 'company')->findOrfail(Auth::user()->id);


        return response()->json(
            [
                'status' => true,
                'user' => $user,
            ],
            200,
        );
    }
}
