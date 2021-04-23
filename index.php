<?php

// Loading Configuration files
require 'config/app.php';

// Loading all classes and libraries
require 'vendor/autoload.php';

$app = new App\Application;
$app->boot();
