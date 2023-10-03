<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login($credentials)
    {
        return DB::transaction(function () use ($credentials) {
            $admin = Admin::where('email', request()->email)->first();

            if (is_null($admin)) {
                throw new ValidationException('Invalid credentials');
            }

            if (!Hash::check($credentials['password'], $admin->password)) {
                throw new ValidationException('Invalid credentials');
            }

            Auth::login($admin);

            return $admin;
        });

    }

    public function createToken($admin)
    {
        return DB::transaction(function () use ($admin) {
            if (!$admin->api_token) {
                $admin->api_token = \Str::random(80);
            }

            $admin->save();

            return $admin;
        });

    }

    public function logout()
    {
        return DB::transaction(function () {
            $admin = auth('api')->user();
            $admin->update(['api_token' => null]);
        });
    }
}
