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

    public function getOrCreateCashierCustomer(): CashierCustomer
    {
        if (!$this->cashierCustomer) {
            $this->cashierCustomer()->save($cashierCustomer = new CashierCustomer());
            $this->setRelation('cashierCustomer', $cashierCustomer);
        }

        return $this->cashierCustomer;
    }
}
