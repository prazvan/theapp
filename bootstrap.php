<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

//-- include composer autoload
include_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

//-- include database connection
include_once 'config'.DIRECTORY_SEPARATOR.'database.php';

//-- include app
include_once 'lib'.DIRECTORY_SEPARATOR.'application.php';