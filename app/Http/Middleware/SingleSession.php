<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();
            $activeSessionId = Cache::get('user_session_' . $user->id);

            // Jika sesi aktif di cache berbeda dengan sesi saat ini, ATAU sesi saat ini sudah dihapus dari tabel sessions di DB
            $sessionExists = \Illuminate\Support\Facades\DB::table('sessions')->where('id', $currentSessionId)->exists();

            if (($activeSessionId && $activeSessionId !== $currentSessionId) || !$sessionExists) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = !$sessionExists 
                    ? 'Sesi Anda telah diakhiri secara paksa dari panel manajemen perangkat.' 
                    : 'Sesi Anda telah berakhir karena akun ini telah login di perangkat atau browser lain.';

                return redirect()->route('login')->with('warning', $message);
            }
        }

        return $next($request);
    }
}
