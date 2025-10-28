<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSeeker
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isSeeker()) {
            abort(403, 'Access denied. Jobseeker access required.');
        }

        return $next($request);
    }
}



