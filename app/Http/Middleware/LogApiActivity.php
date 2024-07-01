<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogApiActivity
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);

    if ($request->is('api/*') && Auth::check()) {
      $user = Auth::user();

      // Log the activity
      activity()
        ->performedOn($user)
        ->withProperties([
          'ip' => $request->ip(),
          'user_agent' => $request->userAgent(),
          'method' => $request->method(),
          'path' => $request->path(),
          'input' => $request->all()
        ])
        ->log('api_action');
    }

    return $response;
  }
}
