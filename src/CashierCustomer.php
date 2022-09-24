<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

/**
 * @property ?string $stripe_account_id
 * @property ?array $account_details
 * @property ?Model $cashierable
 */
class CashierCustomer extends Model
{
    use Billable;

    protected $fillable = [
        'trial_ends_at',
        'account_details'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'account_details' => 'array'
    ];

    public function cashierable(): MorphTo
    {
        return $this->morphTo();
    }

    public function stripeName()
    {
        return Arr::get($this->cashierable?->stripeInfo(), 'name');
    }

    public function stripeEmail()
    {
        return Arr::get($this->cashierable?->stripeInfo(), 'email');
    }

    public function stripePhone()
    {
        return Arr::get($this->cashierable?->stripeInfo(), 'phone');
    }

    public function stripeAddress()
    {
        return Arr::get($this->cashierable?->stripeInfo(), 'address');
    }

    public function stripePreferredLocales()
    {
        return Arr::get($this->cashierable?->stripeInfo(), 'preferredLocales');
    }

    public function stripeAccountId()
    {
        return $this->stripe_account_id;
    }

    public function assertHasStripeAccountId()
    {
        throw_unless($this->stripeAccountId(), "The customer {$this->getKey()} hasn't a Stripe Account");
    }

    public function assertHasntStripeAccountId()
    {
        throw_if($this->stripeAccountId(), "The customer {$this->getKey()} has already a Stripe Account");
    }

    public function createExpressAccount($params = [], $opts = null)
    {
        $this->assertHasntStripeAccountId();

        $account = $this->stripe()->accounts->create(['type' => 'express', ...$params], $opts);

        $this->stripe_account_id = $account->id;
        $this->save();

        return $account;
    }

    public function createAccountLink($params = [], $opts = null)
    {
        return $this->stripe()->accountLinks->create(
            [
                'account' => $this->stripe_account_id,
                ...$params,
            ],
            $opts
        );
    }

    public function createLoginLink()
    {
        return $this->stripe()->accounts->createLoginLink($this->stripe_account_id);
    }

    public function deleteAccount($params = null, $opts = null)
    {
        $this->assertHasStripeAccountId();

        $response = $this->stripe()->accounts->delete($this->stripeAccountId(), $params, $opts);

        $this->stripe_account_id = null;
        $this->save();

        return $response;
    }

    public function asStripeAccount($params = null, $opts = null)
    {
        $this->assertHasStripeAccountId();

        return $this->stripe()->accounts->retrieve($this->stripeAccountId(), $params, $opts);
    }
}
