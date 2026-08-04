<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
    
class RoleMiddlewarev
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    : Response
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->withErrors(['Silahkan Login Terlebih Dahulu.']);
        }

        $userRole = $request->user()->role?->name;

        // dd([
        //     'user_id'   => $request->user()->id,
        //     'role_id'   => $request->user()->role_id,
        //     'userRole'  => $userRole,
        //     'roles_dibutuhkan' => $roles,
        // ]);
        // Jika nama role tidak ada di dalam daftar array yang diminta, tendang ke 403
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthhorized');
        }
        return $next($request);
    }
}
