<?php

require_once '../vendor/autoload.php';

// Aways after autoload require
session_start();

require_once '../App/Config/bootstrap.php';
require_once '../App/Routes/web.php';
