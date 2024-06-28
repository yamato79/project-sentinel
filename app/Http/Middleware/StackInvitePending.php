<?php

namespace App\Http\Middleware;

use App\Models\Stack;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StackInvitePending
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $stack = $request->route()->parameter('stack');

        if (
            $request->route()->parameter('stack') &&
            ! ($request->route()->parameter('stack') instanceof Stack)) {
            return $next($request);
        }

        $userInStack = $stack
            ->users()
            ->where('pivot_stacks_users.user_id', $user->getKey())
            ->exists();

        $userInStackAcceptedInvite = $stack
            ->users()
            ->where('pivot_stacks_users.user_id', $user->getKey())
            ->whereNotNull('pivot_stacks_users.joined_at')
            ->exists();

        if ($userInStack && ! $userInStackAcceptedInvite) {
            return redirect()->route('panel.stacks.invitation', [
                'stack' => $stack,
            ]);
        }

        return $next($request);
    }
}
