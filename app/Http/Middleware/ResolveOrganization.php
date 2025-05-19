<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Organization;
use Symfony\Component\HttpFoundation\Response;

class ResolveOrganization
{
    /**
     * Handle an incoming request.
     *
     * Resolves the {org} route parameter and attaches the Organization model
     * instance to the request as an attribute.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('org');

        $organization = Organization::where('slug', $slug)->first();

        if (!$organization) {
            return response()->json(['error' => 'Organization not found.'], 404);
        }

        // Attach the resolved organization to the request attributes
        $request->attributes->set('organization', $organization);

        return $next($request);
    }
}
