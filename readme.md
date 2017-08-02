# Whats A Tool - Client

## Description

This library gives you the basic functionality to communicate with the Whats A Tool API.

[Whats a Tool](https://atms.at/de/whatsatool)

## Installation

First require in Composer:

`composer require campaigningbureau/whatsatool-client`

Add the Service Provider in your `config/app.php`:

```php
'providers' => [
    ...
    CampaigningBureau\WhatsAToolClient\WhatsAToolClientProvider::class,
]
```

You can also add the Facade there:

```php
'aliases' => [
    ...
    'WhatsAToolClient' =>  CampaigningBureau\WhatsAToolClient\WhatsAToolClientFacade::class,
]
```

Publish the config settings:

```
$ php artisan vendor:publish
```

## Configuration

After publishing the config file you can edit them in `config/whatsatool.php`.

Make sure you configure at least `username` and `password`.

The `default_country_code` is used to create the correct MSISDN when a phonenumber
with local country code is given (e.g. *0664/1234567*)

## Usage

To validate a given number:
```php
$phonenumber = '+43 664 123 456 87';
Msisdn::validatePhonenumber($phonenumber);
```

To register a new Contact:
```php
$phonenumber = '+43 664 123 456 87';
$msisdn = new Msisdn($phonenumber);
WhatsATool::registerContact($msisdn, $channel, $sendSms);
```

## Development

If you have PHPUnit installed in your environment, run:
```
$ phpunit
```

If you don't have PHPUnit installed, you can run the following:
```
$ composer update
$ ./vendor/bin/phpunit
```