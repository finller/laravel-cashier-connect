<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Customer extends Model
{
    use Billable;

    protected $fillable = [
        'trial_ends_at',
    ];

    protected $dates = [
        'trial_ends_at',
    ];

    public function cashierable(): MorphTo
    {
        return $this->morphTo();
    }

    public function stripeName()
    {
        return $this->cashierable?->stripeName();
    }

    public function stripeEmail()
    {
        return $this->cashierable?->stripeEmail();
    }

    public function stripePhone()
    {
        return $this->cashierable?->stripePhone();
    }

    public function stripeAddress()
    {
        return $this->cashierable?->stripeAddress();
    }

    public function createExpressAccount($params = null, $opts = null)
    {
        $this->stripe()->accounts->create(['type' => 'express', ...$params], [...$opts]);
    }
}
