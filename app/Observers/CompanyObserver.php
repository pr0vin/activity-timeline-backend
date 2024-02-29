<?php

namespace App\Observers;

use App\Models\Company;

class CompanyObserver
{
    /**
     * Handle the Company "updating" event.
     */

    public function creating(Company $company)
    {
        $company->expiry_date = now()->addYear();
    }


    public function updating(Company $company)
    {
        // Check if expiry date is reached
        if ($company->id != 1 && $company->expiry_date && $company->expiry_date < now()) {
            $company->status = 0; // Expired
        }
    }

    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        //

    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}
