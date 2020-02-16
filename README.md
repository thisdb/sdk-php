# ThisDB SDK for PHP

[![Latest Version](https://img.shields.io/github/release/thisdb/php-sdk.svg?style=flat-square)](https://github.com/thisdb/php-sdk/releases)

Official ThisDB SDK for PHP

Website and documentation: https://www.thisdb.com

The cloud key/value database built from the ground up for serverless applications. It's fast, secure, cost effective, and is easy to integrate.

```
$thisDB = new \ThisDB\Client('<your-api-key>');

echo $thisDB->get('<your-bucket>', '<your-key>');
```

## Installation

The recommended way to install is through
[Composer](https://getcomposer.org/).

```bash
composer require thisdb/sdk-php
```
