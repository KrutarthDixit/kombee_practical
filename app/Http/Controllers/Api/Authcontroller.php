<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Symfony\Component\HttpFoundation\Response;

class Authcontroller extends Controller
{
    use ApiResponseTrait;

    /**
     * Function to handle user registration
     */
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        event(new Registered($user));

        return $this->successResponse([
            'data' => $user,
            'message' => 'Registration successful. Please verify your email.',
            'code' => Response::HTTP_CREATED,
        ]);
    }

    /**
     * Function to handle user login
     */
    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt($data['email'], $data['password'])) {
            return $this->errorResponse('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return $this->errorResponse('Email not verified', Response::HTTP_FORBIDDEN);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Login successful',
            'code' => Response::HTTP_OK,
        ]);
    }

    /**
     * Function to handle email verification
     */
    public function verifyEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->errorResponse('Email already verified', Response::HTTP_OK);
        }

        $request->user()->markEmailAsVerified();

        return $this->successResponse([
            'message' => 'Email verified successfully',
            'code' => Response::HTTP_OK,
        ]);
    }

    /**
     * Function to resend email verification link
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->errorResponse('Email already verified', Response::HTTP_OK);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->successResponse([
            'message' => 'Verification email sent successfully',
            'code' => Response::HTTP_OK,
        ]);
    }

    /**
     * Function to handle user logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse([
            'message' => 'Logout successful',
            'code' => Response::HTTP_OK,
        ]);
    }
}
