<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property ?CashierCustomer $customer
 */
trait Cashierable
{
    public function customer(): MorphOne
    {
        return $this->morphOne(CashierCustomer::class, 'cashierable');
    }
}
