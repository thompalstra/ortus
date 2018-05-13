<?php
include( __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Application.php' );
$app = new Application();
$app->handleRequest( $app->parseRequest() );
