<?php

namespace App\Http\Middleware;

use App\Models\AllowedUrls;
use Illuminate\Http\Request;
use App\Models\Urls;
use Closure;

class CheckUrlAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


        // Get the current route's URL (or path)
        $currentUrl = $request->path();
        $pathSegments = explode('/', ltrim($currentUrl, "/"));
        $current_page =  $pathSegments[0];

        $url = Urls::where('url', $current_page)->first();
        $user = auth()->user();

        if (!$url) {
            return $next($request);
        }

        $isUrlApproved = AllowedUrls::where('user_id', $user->id)
            ->orWhere('role_id', $user->role_id)
            ->exists();


        if (!$isUrlApproved) {
            return redirect()->route('access_denied');  // You can define this route
        }

        return $next($request);
    }
}
