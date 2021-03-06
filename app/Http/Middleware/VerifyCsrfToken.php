<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'proposal',
        'proposal/propose',
        '/luckydrawresult',
        // '/proposal/serverapproval',
        // '/proposal/servercompletion',
        // '/proposal/cancel',
        // '/proposal/error',
        // '/proposal/incomplete',
        // '/proposal/checkproposal',
        // '/donatelog/getuserbyproposalid',
    ];
}
