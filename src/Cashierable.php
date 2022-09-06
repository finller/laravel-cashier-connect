<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property ?Customer $customer
 */
trait Cashierable
{
    public function customer(): MorphOne
    {
        return $this->morphOne(Customer::class, 'cashierable');
    }
}
