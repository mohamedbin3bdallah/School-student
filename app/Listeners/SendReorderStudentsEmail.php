<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ReorderStudentsEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendReorderStudentsEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ReorderStudentsEmail  $event
     * @return void
     */
    public function handle(ReorderStudentsEmail $event)
    {
		$user = ($event->user)->toArray();
        Mail::send('emails.ReorderStudents', $user, function($message) use ($user) {
            $message->to($user['email']);
            $message->subject('Reorder Students');
        });
    }
}
