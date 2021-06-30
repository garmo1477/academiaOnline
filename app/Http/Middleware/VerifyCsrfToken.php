<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     * damos acceso solamente a stripe para que pueda entrar sin enviar csrfToken
     */
    protected $except = [
        'stripe/*',
    ];
}
