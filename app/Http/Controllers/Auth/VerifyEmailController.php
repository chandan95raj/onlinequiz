<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Verify the user's email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($hash !== sha1($user->getEmailForVerification())) {
            return redirect('/')->with('error', 'Invalid verification link');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('status', 'Your email is already verified');
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return redirect('/')->with('status', 'Email successfully verified');
    }
}
