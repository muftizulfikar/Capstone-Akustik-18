<?php
$_dir = __DIR__;
define('env', $_dir);
include(env . '/env.php');


// utils
include 'vendor/autoload.php';
include 'utils/utils.php';
include 'utils/db.php';
include 'utils/session.php';
