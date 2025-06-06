<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailViewResponse as VerifyEmailViewResponseContract;
use Illuminate\Contracts\Support\Responsable;

class CustomVerifyEmailViewResponse implements VerifyEmailViewResponseContract, Responsable
{
    public function toResponse($request)
    {
        return view('auth.verify-email');
    }
}
