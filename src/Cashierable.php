<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property ?CashierCustomer $cashierCustomer
 */
trait Cashierable
{
    public function cashierCustomer(): MorphOne
    {
        return $this->morphOne(CashierCustomer::class, 'cashierable');
    }
}
