<?php

namespace Mojahed\Spa;

use Illuminate\Http\Request;

class SpaRequest
{
    /**
     * Determine if the current request is an SPA fragment request.
     */
    public static function isFragment(?Request $request = null): bool
    {
        $request = $request ?? request();
        return $request->hasHeader('X-SPA-Request');
    }

    /**
     * Return a fragment view if SPA request, otherwise the full view.
     *
     * Native fragment extraction requires Laravel >= 9.36 (the @fragment
     * directive / View::fragmentIf()). On older versions the full view is
     * returned unchanged, since fragment markers are not available.
     *
     * Usage in controller:
     *   return SpaRequest::fragmentIf($request, view('employees.index', $data), 'list');
     */
    public static function fragmentIf(Request $request, $view, string $fragment)
    {
        if (! static::isFragment($request)) {
            return $view;
        }

        // Native fragment support (Laravel >= 9.36)
        if (method_exists($view, 'fragmentIf')) {
            return $view->fragmentIf(true, $fragment);
        }

        // Fallback: fragments are unavailable on this Laravel version.
        return $view;
    }
}
