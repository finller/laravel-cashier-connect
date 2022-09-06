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
        $account = $this->stripe()->accounts->create(['type' => 'express', ...$params], $opts);

        $this->stripe_account_id = $account->id;
        $this->save();

        return $account;
    }

    public function createAccountLink($params = null, $opts = null)
    {
        return $this->stripe()->accountLinks->create(
            [
                'account' => $$this->stripe_account_id,
                ...$params,
            ],
            $opts
        );
    }
}
