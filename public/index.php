<?php

require_once '../vendor/autoload.php';

// Aways after autoload require
session_start();

require_once '../app/config/bootstrap.php';
require_once '../app/routes/web.php';
