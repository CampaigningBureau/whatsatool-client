# Whats A Tool - Client

## Description

This library gives you the basic functionality to communicate with the Whats A Tool API.

[Whats a Tool](https://atms.at/de/whatsatool)
[MSISDN on Wikipedia](https://en.wikipedia.org/wiki/MSISDN)

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

### Validation

To validate a given number:
```php
$phonenumber = '+43 664 123 456 87';
CampaigningBureau\WhatsAToolClient\Msisdn::validatePhonenumber($phonenumber);
```
The Validation cleans the phonenumber, that means **removes all non-numeric characters and leading `00`** and checks if:
1. The cleaned phonenumber is not empty
2. The length is shorter or equal to 15 characters (funny fact: there is no official minimum lengt restriction)
3. The phonenumber starts with a valid **Country Code**, which is either `0` for local or a country code that can be found
in [this](https://gist.github.com/josephilipraja/8341837) list.

### Register Contact

To register a new Contact:
```php
$phonenumber = '+43 664 123 456 87';
$msisdn = new Msisdn($phonenumber);
try {
    $simMsisdn = WhatsATool::registerContact($msisdn, $channel, $sendSms);
} catch (WhatsAToolException $exception) {
    Log::error($exception->getMessage());
}
```
This returns the msisdn of the Sim the number is registered to or throws a `WhatsAToolException` with the error message
of the WhatsATool-API.

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

## Credits

The Msisdn-Logic is based on [https://github.com/CoreProc/msisdn-ph-php/](https://github.com/CoreProc/msisdn-ph-php/).