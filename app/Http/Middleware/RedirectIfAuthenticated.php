<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response; // penting: Response yang benar

class RedirectIfAuthenticated
{
    /**
     * Jika user sudah login dan masuk ke rute 'guest',
     * arahkan sesuai role.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user && $user->role === 'admin') {
                    try {
                        return redirect()->route('filament.admin.pages.dashboard');
                    } catch (\Throwable $e) {
                        return redirect()->to('/admin'); // fallback
                    }
                }

                // pengguna -> landing page
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
