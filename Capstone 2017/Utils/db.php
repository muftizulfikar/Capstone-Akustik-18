<?php
$DB_HOST = getenv('DB_HOST');
$DB_USERNAME = getenv('DB_USERNAME');
$DB_PASSWORD = getenv('DB_PASSWORD');
$DB_DATABASE = getenv('DB_DATABASE');

$db = new MysqliDb($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

