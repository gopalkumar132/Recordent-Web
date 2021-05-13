<?php

namespace App\Traits;
use Auth;

trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null(Auth::user()->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => \Carbon\Carbon::now(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    { 
        $this->notify(new \App\Notifications\VerifyEmail);
    }
}
