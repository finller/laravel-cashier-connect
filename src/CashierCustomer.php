<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
