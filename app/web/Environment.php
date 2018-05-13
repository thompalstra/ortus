<?php
namespace app\web;
class Environment extends \app\core\Base{
  public function __construct(){

    $root = \Core::$app->root;
    $ds = \Core::$app->ds;

    $parts = explode( ".", $_SERVER["HTTP_HOST"] );
    if( count( $parts ) > 2 ) {
      $this->name = $parts[ 0 ];
    }

    $this->name = ( count( $parts ) > 2 ) ? $parts[ 0 ] : \Core::$app->config["environment"]["default"];
    $this->directory = "env{$ds}{$this->name}{$ds}";
  }
}
?>
