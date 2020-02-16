<?php
require_once __DIR__ . '/../vendor/autoload.php';

$thisDB = new \ThisDB\Client('<your-api-key>');

echo $thisDB->get('<your-bucket>', '<your-key>');