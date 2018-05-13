<?php
namespace app\web;
class EventManager extends \app\core\Base{
  public function dispatchEvent( \app\web\Event $event ){

    $root = \Core::$app->root;
    $ds = \Core::$app->ds;

    foreach( scandir( "{$root}plugins" ) as $companyName ){
      if( $companyName == "." || $companyName == ".." ){ continue; }
      foreach( scandir( "{$root}plugins{$ds}{$companyName}" ) as $pluginName ){
        if( $pluginName == "." || $pluginName == ".." ){ continue; }
        $pluginEventsNamespace = \Core::$app->normalizeNamespace( "{$companyName}/{$pluginName}/Events" );
        if( isset( $pluginEventsNamespace::$events[ $event->type ] ) ){
          foreach( $pluginEventsNamespace::$events[ $event->type ] as $functionName ){
            if( !$event->defaultPrevented ){
              if( !method_exists( $pluginEventsNamespace, $functionName ) ){
                header("HTTP/1.0 500 Internal Server Error");
                throw new \Exception( "Missing function: {$pluginEventsNamespace}::$functionName( \app\web\Event \$event )", 500 );
                exit();
              }
              call_user_func_array( [ $pluginEventsNamespace, $functionName ], [ $event ] );
            }
          }
        }
      }
    }
  }
}
?>
