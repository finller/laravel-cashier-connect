<p align="center"><img src="/art/logo.svg" alt="Logo Laravel Cashier Stripe"></p>

<p align="center">
<a href="https://github.com/laravel/cashier/actions"><img src="https://github.com/laravel/cashier/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/cashier"><img src="https://img.shields.io/packagist/dt/laravel/cashier" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/cashier"><img src="https://img.shields.io/packagist/v/laravel/cashier" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/cashier"><img src="https://img.shields.io/packagist/l/laravel/cashier" alt="License"></a>
</p>

## Introduction

Laravel Cashier provides an expressive, fluent interface to [Stripe's](https://stripe.com) subscription billing services. It handles almost all of the boilerplate subscription billing code you are dreading writing. In addition to basic subscription management, Cashier can handle coupons, swapping subscription, subscription "quantities", cancellation grace periods, and even generate invoice PDFs.

## Official Documentation

Documentation for Cashier can be found on the [Laravel website](https://laravel.com/docs/billing).

## Contributing

Thank you for considering contributing to Cashier! You can read the contribution guide [here](.github/CONTRIBUTING.md).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Please review [our security policy](https://github.com/laravel/cashier/security/policy) on how to report security vulnerabilities.

## License

Laravel Cashier is open-sourced software licensed under the [MIT license](LICENSE.md).

## Sync with laravel/cashier-stripe

Add the remote, call it "upstream":

`git remote add upstream https://github.com/whoever/whatever.git`

Fetch all the branches of that remote into remote-tracking branches

`git fetch upstream`

Make sure that you're on your master branch:

`git checkout 15.x`

Rewrite your master branch so that any commits of yours that
aren't already in upstream/master are replayed on top of that
other branch:

`git rebase upstream/15.x`
