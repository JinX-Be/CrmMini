<?php

namespace App\Http\Middleware;

use App\Support\Niche;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ResolveNiche
{
    /**
     * Resolve the active niche for this request and make it available
     * everywhere (container + all views as $niche).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveKey($request);

        $niche = Niche::fromKey($key);

        // Available via app(Niche::class) / dependency injection...
        app()->instance(Niche::class, $niche);

        // ...and in every Blade view as $niche.
        View::share('niche', $niche);

        return $next($request);
    }

    /**
     * Decide which niche applies.
     *
     * Phase 0-4: config value.
     * Phase 5: uncomment the hostname lookup to resolve per domain/subdomain.
     */
    protected function resolveKey(Request $request): string
    {
        // Phase 5 — resolve by URL:
        // $byDomain = config('niche.domains.'.$request->getHost());
        // if (is_string($byDomain)) {
        //     return $byDomain;
        // }

        return config('niche.active', 'window_cleaning');
    }
}
