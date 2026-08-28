<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    // Routes that are exempt from forced password change redirect
    protected array $except = [
        'password/force-change',
        'password/force-change/update',
        'logout',
        'profile/sessions',
        'profile/sessions/*',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (
            auth()->check() &&
            auth()->user()->need_password_change &&
            !$this->isExcluded($request)
        ) {
            return redirect()->route('password.force.change')
                ->with('info', 'Anda harus mengganti password akun Anda sebelum melanjutkan. Gunakan password yang kuat.');
        }

        return $next($request);
    }

    private function isExcluded(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }
        return false;
    }
}
