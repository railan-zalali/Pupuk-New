<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovalStatus
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
            
            // If user is not approved, log them out and redirect
            if (!$user->isApproved()) {
                Auth::logout();
                
                $message = match($user->approval_status) {
                    'pending' => 'Akun Anda masih menunggu persetujuan admin.',
                    'rejected' => 'Akun Anda telah ditolak oleh admin.',
                    default => 'Akun Anda belum disetujui.'
                };
                
                return redirect()->route('login')->withErrors(['email' => $message]);
            }
        }

        return $next($request);
    }
}