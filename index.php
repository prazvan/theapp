<?php

//-- first we need to include the bootstrap
include_once 'bootstrap.php';

//-- register new app
$App = App::register(array
(
    'db_connection' => $connection
));

//-- route application
$App->route($_REQUEST['view'] ?: 'index');