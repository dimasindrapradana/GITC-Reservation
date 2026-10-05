<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        /*
         * Only active users are allowed to log in.
         *
         * Users with deleted_at IS NOT NULL are
         * considered soft deleted and cannot authenticate.
         */
        $loginCredentials = [
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'deleted_at' => null,
        ];

        if (! Auth::attempt($loginCredentials)) {
            throw ValidationException::withMessages([
                'username' => 'Invalid username or password.',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Training Officer Classroom
        |--------------------------------------------------------------------------
        */

        if ($user?->role?->name === 'Training Officer Classroom') {
            return redirect()->route(
                'training-officer.classroom.home'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Training Officer
        |--------------------------------------------------------------------------
        */

        if ($user?->role?->name === 'Training Officer') {
            return redirect()->route(
                'training-officer.home'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Coordinator
        |--------------------------------------------------------------------------
        */

        if ($user?->role?->name === 'Coordinator') {
            return redirect()->route(
                'coordinator.dashboard'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Building Coordinator
        |--------------------------------------------------------------------------
        */

        if ($user?->role?->name === 'Building Coordinator') {
            return redirect()->route(
                'building-coordinator.dashboard'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Field Coordinator
        |--------------------------------------------------------------------------
        */

        if ($user?->role?->name === 'Field Coordinator') {
            return redirect()->route(
                'field-coordinator.reservations.index'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}