<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class CheckIfUserCanAccessListing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Listing::where(['id' => $request->key, 'user_id' => Auth::user()->id])->count() === 0) {
            abort(403, 'Access denied');
        }
        return $next($request);
    }
}
