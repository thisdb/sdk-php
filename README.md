# thisdb-php-sdk

[![Latest Version](https://img.shields.io/github/release/thisdb/thisdb-php.svg?style=flat-square)](https://github.com/thisdb/thisdb-php/releases)

Official PHP SDK for ThisDB

Website and documentation: https://www.thisdb.com

The cloud key/value database built from the ground up for serverless applications. It's fast, secure, cost effective, and is easy to integrate.

```
$thisDB = new \ThisDB\Client('<your-api-key>');

echo $thisDB->get('<your-bucket>', '<your-key>');
```

## Installation

The recommended way to install thisdb-php is through
[Composer](https://getcomposer.org/).

```bash
composer require thisdb/thisdb-php
```
