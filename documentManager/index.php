<?php
// I wrote a very simple router here. Using the .htaccess rules to redirect all requests to index.php

require 'constants.php';
require 'DocumentManagerController.php';
require 'Response.php';

$request = $_SERVER['REDIRECT_URL'];
$parts = explode("/", $request);
$action = $parts[2];

$controller = new DocumentManagerController();

header('Content-Type: application/json');
switch($action) {
    case 'upload': 
    case 'getAll':
        echo $controller->$action();
        break;
    case 'delete':
        $fileName = (isset($_POST["fileName"])) ? $_POST["fileName"] : '';
        echo $controller->$action($fileName);
        break; 
}

?>