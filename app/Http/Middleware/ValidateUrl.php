<?php

namespace App\Http\Middleware;

use Closure;

class ValidateUrl
{
    public function handle($request, Closure $next)
    {
        $url = $request->input('url_image');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid URL for the film image.');
        }

        return $next($request);
    }
}
