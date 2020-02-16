# thisdb-php

Official PHP library for ThisDB

Website and documentation: https://www.thisdb.com

## Installation

- Download the phar from the latest [release](https://github.com/thisdb/thisdb-php/releases)
- Include in your application

```
require 'app.phar';

$apiKey = '<your-api-key>';
$thisDB = new ThisDB($apiKey);

echo $thisDB->get('<your-bucket>', '<your-key>');
```
