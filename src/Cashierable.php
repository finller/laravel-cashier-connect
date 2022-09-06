<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Relations\MorphOne;

use function Illuminate\Events\queueable;

/**
 * @property ?CashierCustomer $cashierCustomer
 */
trait Cashierable
{

    public static function bootCashierable()
    {
        static::updated(queueable(function ($customer) {
            if ($customer->cashierCustomer?->hasStripeId()) {
                $customer->cashierCustomer?->syncStripeCustomerDetails();
            }
        }));
    }

    public function cashierCustomer(): MorphOne
    {
        return $this->morphOne(CashierCustomer::class, 'cashierable');
    }

    public function getOrCreateCashierCustomer(): CashierCustomer
    {
        if (!$this->cashierCustomer) {
            $this->cashierCustomer()->save($cashierCustomer = new CashierCustomer());
            $this->setRelation('cashierCustomer', $cashierCustomer);
            return $cashierCustomer;
        }

        return $this->cashierCustomer;
    }

    public function stripeInfo(): array
    {
        return [];
    }
}
