# payaza-api-client-php

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/payaza-api-client-php)](https://github.com/brokeyourbike/payaza-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/payaza-api-client/downloads)](https://packagist.org/packages/brokeyourbike/payaza-api-client)
[![Maintainability](https://api.codeclimate.com/v1/badges/02f2cba0a13a05bda811/maintainability)](https://codeclimate.com/github/brokeyourbike/payaza-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/02f2cba0a13a05bda811/test_coverage)](https://codeclimate.com/github/brokeyourbike/payaza-api-client-php/test_coverage)

Payaza API Client for PHP

## Installation

```bash
composer require brokeyourbike/payaza-api-client
```

## Usage

```php
use BrokeYourBike\Payaza\Interfaces\ConfigInterface;
use BrokeYourBike\Payaza\Client;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);

$apiClient = new Client($config, $httpClient);
$apiClient->payout($transaction);
```

## Authors
- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[BSD-3-Clause License](https://github.com/brokeyourbike/payaza-api-client-php/blob/main/LICENSE)
