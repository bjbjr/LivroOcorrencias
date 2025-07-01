<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Verifica se o usuário está autenticado
            // e se o tipo de usuário é 'admin'
            if (Auth::user()->type === 'admin') {
                return $next($request);
            }
        }

        // Se não for admin ou não estiver logado:
        if (Auth::check() && Auth::user()->type !== 'admin') {
            // Usuário logado mas não é admin, redireciona para o dashboard padrão com uma mensagem
            // A mensagem 'status' é frequentemente usada pelo Breeze para feedback.
            return redirect()->route('dashboard')->with('status', 'Acesso não autorizado para esta área.');
        }

        // Usuário não logado, redireciona para o login
        // Usar 'status' para consistência com o Breeze, ou 'error' se preferir diferenciar.
        return redirect()->route('login')->with('status', 'Você precisa estar logado como administrador para acessar esta área.');
    }
}
