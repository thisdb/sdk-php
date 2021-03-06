# ThisDB PHP SDK

[![Latest Version](https://img.shields.io/github/release/thisdb/sdk-php.svg?style=flat-square)](https://github.com/thisdb/sdk-php/releases)

Official ThisDB SDK for PHP

Website and documentation: https://www.thisdb.com

The cloud key/value database built from the ground up for serverless applications. It's fast, secure, cost effective, and is easy to integrate.

```php
$thisDB = new \ThisDB\Client(['apiKey' => '<your-api-key>']);

echo $thisDB->get(['bucket' => '<your-bucket>', 'key' => '<your-key>']);
```

## Installation

The recommended way to install is through
[Composer](https://getcomposer.org/).

```bash
composer require thisdb/sdk-php
```
