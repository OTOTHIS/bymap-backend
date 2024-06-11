<?php
// namespace App\Http\Requests\Auth;

// use Illuminate\Auth\Events\Lockout;
// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\RateLimiter;
// use Illuminate\Support\Str;

// class LoginRequest extends FormRequest
// {
//     public function rules()
//     {
//         return [
//             'email' => 'required|string|email',
//             'password' => 'required|string',
//         ];
//     }

//     public function authenticate()
//     {
//         $this->ensureIsNotRateLimited();

//         if (! Auth::guard('web')->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
//             throw ValidationException::withMessages([
//                 'email' => __('auth.failed'),
//             ]);
//         }
//     }

//     public function ensureIsNotRateLimited()
//     {
//         if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
//             return;
//         }

//         event(new Lockout($this));

//         $seconds = RateLimiter::availableIn($this->throttleKey());

//         throw ValidationException::withMessages([
//             'email' => trans('auth.throttle', [
//                 'seconds' => $seconds,
//                 'minutes' => ceil($seconds / 60),
//             ]),
//         ]);
//     }

//     public function throttleKey()
//     {
//         return Str::lower($this->input('email')).'|'.$this->ip();
//     }
// }


namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        foreach (array_keys(config('auth.guards')) as $guard) {
            if (Auth::guard($guard)->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                return;
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]),
        ]);
    }

    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
