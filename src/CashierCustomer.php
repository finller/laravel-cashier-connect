<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Laravel\Cashier\Database\Factories\CashierCustomerFactory;

/**
 * @property ?string $stripe_account_id
 * @property ?array $account_details
 * @property ?Model $cashierable
 * @property string $cashierable_type
 * @property int $cashierable_id
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

    public function stripeAccountId(): ?string
    {
        return $this->stripe_account_id;
    }

    public function assertHasStripeAccountId(): void
    {
        throw_unless($this->stripeAccountId(), "The customer {$this->getKey()} hasn't a Stripe Account");
    }

    public function assertHasntStripeAccountId(): void
    {
        throw_if($this->stripeAccountId(), "The customer {$this->getKey()} has already a Stripe Account");
    }

    public function createExpressAccount($params = [], $opts = null): \Stripe\Account
    {
        $this->assertHasntStripeAccountId();

        $account = $this->stripe()->accounts->create(['type' => 'express', ...$params], $opts);

        $this->stripe_account_id = $account->id;
        $this->save();

        return $account;
    }

    public function createAccountLink($params = [], $opts = null): \Stripe\AccountLink
    {
        $this->assertHasStripeAccountId();

        return $this->stripe()->accountLinks->create(
            [
                'account' => $this->stripeAccountId(),
                ...$params,
            ],
            $opts
        );
    }

    public function createLoginLink(): \Stripe\LoginLink
    {
        $this->assertHasStripeAccountId();

        return $this->stripe()->accounts->createLoginLink($this->stripeAccountId());
    }

    public function deleteAccount($params = null, $opts = null): \Stripe\Account
    {
        $this->assertHasStripeAccountId();

        $response = $this->stripe()->accounts->delete($this->stripeAccountId(), $params, $opts);

        $this->stripe_account_id = null;
        $this->account_details = null;
        $this->save();

        return $response;
    }

    public function updateAccount($params = null, $opts = null): \Stripe\Account
    {
        $this->assertHasStripeAccountId();

        return $this->stripe()->accounts->update(
            $this->stripeAccountId(),
            $params,
            $opts
        );
    }

    public function asStripeAccount($params = null, $opts = null): \Stripe\Account
    {
        $this->assertHasStripeAccountId();

        return $this->stripe()->accounts->retrieve($this->stripeAccountId(), $params, $opts);
    }

    public function payoutAccount($params = null, $opts = []): \Stripe\Payout
    {
        $this->assertHasStripeAccountId();

        return $this->stripe()->payouts->create($params, [
            ...$opts,
            'stripe_account' => $this->stripeAccountId(),
        ]);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CashierCustomerFactory::new();
    }
}
