<?php

namespace App\Traits;

trait MustSendEmail
{
    /**
     * Determine if the user has sent their email address.
     *
     * @return bool
     */
    public function hasSentEmail($User)
    {
        return ! is_null($User->email_sent_at);
    }

    /**
     * Mark the given user's email as sent.
     *
     * @return bool
     */
    public function markEmailAsSent()
    {
        return $this->forceFill([
            'email_sent_at' => \Carbon\Carbon::now(),
        ])->save();
    }

}
