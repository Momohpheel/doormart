<?php

namespace App\Observers;

use App\Models\Agency;
use App\Notifications\AgencyWelcomeEmail;


class AgencyObserver
{
     /**
     * Handle the Vendor "created" event.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return void
     */
    public function created(Agency $agency)
    {
        $agency->notify(new AgencyWelcomeEmail($agency));
    }
}
