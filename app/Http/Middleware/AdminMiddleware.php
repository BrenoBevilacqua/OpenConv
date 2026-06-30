<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifique se o usuário está autenticado e se ele é admin
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isAdminMaster())) {
            return $next($request); // Se for admin, continua a execução
        }

        // Se o usuário não for admin ou não estiver autenticado, retorna erro 403
        abort(403, 'Acesso não autorizado.');
    }
}