<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Unpaid reservation hold
    |--------------------------------------------------------------------------
    |
    | Minutes a checkout may keep seats and an open PaymentIntent before the
    | scheduler releases the stock and cancels the payment attempt.
    |
    */

    'unpaid_hold_minutes' => (int) env('RESERVATION_UNPAID_HOLD_MINUTES', 15),

];
