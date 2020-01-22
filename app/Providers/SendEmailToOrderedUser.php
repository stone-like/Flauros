<?php

namespace App\Providers;

use App\Providers\OrderCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendOrderCompletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailToOrderedUser
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
     * @param  OrderCompleted  $event
     * @return void
     */
    public function handle(OrderCompleted $event)
    {
        Mail::to($event->user)->send(new SendOrderCompletedMail($event->user,$event->order));
    }
}
