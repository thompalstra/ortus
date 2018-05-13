<?php
spl_autoload_register( function( $className ) {
  $root = \Core::$app->root;
  $ds = \Core::$app->ds;

  $directFilePath = \Core::$app->normalizePath( "{$root}{$className}.php" );
  if( file_exists( $directFilePath ) ){
    include( $directFilePath );
  }

  $pluginsFilePath = \Core::$app->normalizePath( "{$root}plugins{$ds}{$className}.php" );

  if( file_exists( $pluginsFilePath ) ){
    include( $pluginsFilePath );
  }

  if( property_exists( \Core::$app, "environment" ) ){
    $environmentDirectory = \Core::$app->environment->directory;
    $environmentFilePath = \Core::$app->normalizePath( "{$root}env{$ds}{$className}.php" );
    if( file_exists( $environmentFilePath ) ){
      return include( $environmentFilePath );
    }
  }

  return false;
} );
?>
