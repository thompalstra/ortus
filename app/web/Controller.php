<?php
namespace app\web;
class Controller extends \app\core\Base{

  public function render( $fp, $data = [] ){

    $ds = \Core::$app->ds;
    $root = \Core::$app->root;
    $environmentDirectory =  \Core::$app->environment->directory;

    if( $fp[0] == "/" || $fp[0] == $ds ){
      $fp = substr( $fp, 1, strlen( $fp ) );
      $sourcePath = "";
    } else {
      $sourcePath = debug_backtrace()[0]['file'];
      $sourcePath = str_replace( \Core::$app->controller->name, \Core::$app->controller->id, $sourcePath );
      $sourcePath = str_replace( "controllers", "views", $sourcePath );
      $sourcePath = str_replace( ".php", "", $sourcePath );
      $sourcePath = str_replace( \Core::$app->root, "", $sourcePath );
      $fp = "{$ds}{$fp}";
    }
    $layout = \Core::$app->controller->layout;
    $viewFilePath = "{$ds}{$sourcePath}{$fp}";
    $layoutFilePath = "{$ds}{$environmentDirectory}views{$ds}layouts{$ds}{$layout}";

    header("HTTP/1.0 200 OK");
    echo \Core::$app->renderer->renderFile( $layoutFilePath, [
      "content" => \Core::$app->renderer->renderFile( $viewFilePath )
    ] );
    exit();
  }

  public function runAction( $actionId, $params ){
    if( method_exists( $this, 'beforeAction' ) && call_user_func_array( [ $this, 'beforeAction' ], [ $actionId ] ) !== true ){
      call_user_func_array( [ $this, 'runError' ], [ "Not Allowed", 405 ] );
      exit();
    }

    $actionName = self::actionFromId( $actionId );
    if( method_exists( $this, $actionName ) ){
      call_user_func_array( [ $this, $actionName ], $params );
    } else {
      call_user_func_array( [ $this, 'runError' ], [ "Not Found", 404 ] );
    }

    if( method_exists( $this, 'afterAction' ) && call_user_func_array( [ $this, 'afterAction' ], [ $actionId ] ) !== true ){
      call_user_func_array( [ $this, 'runError' ], [ "Not Allowed", 405 ] );
      exit();
    }
  }

  public function fromRoute( $route ){

    $root = \Core::$app->root;
    $ds = \Core::$app->ds;

    $parts = explode( "/", $route[0] );
    $params = $route[1];

    $environmentName = \Core::$app->environment->name;
    $environmentDirectory = \Core::$app->environment->directory;

    $actionId =       ( count( $parts ) > 1 ) ?
                        array_pop( $parts ) :
                        \Core::$app->config["controller"]["actionDefault"];
    $controllerId =   ( count( $parts ) > 1 ) ?
                        array_pop( $parts ) :
                        \Core::$app->config["controller"]["default"];
    $path =           ( count( $parts ) > 1 ) ?
                        implode( "/", $parts ) . "/" :
                        "/";

    $controllerName = self::nameFromId( $controllerId );
    $controllerNameSpace = \Core::$app->normalizeNamespace( "{$environmentName}{$ds}controllers{$path}{$controllerName}" );

    if( class_exists( $controllerNameSpace ) ){
      $actionName = self::actionFromId( $actionId );
      $args = [
        "id" => $controllerId,
        "name" => $controllerName,
        "nameSpace" => $controllerNameSpace,
        "actionId" => $actionId,
        "layout" => \Core::$app->config["controller"]["layout"],
        "directory" => \Core::$app->normalizePath( "{$environmentName}{$ds}controllers{$path}" ),
        "viewPath" => \Core::$app->normalizePath( "{$environmentName}{$ds}views{$path}{$controllerId}{$ds}" ),
        "layoutPath" => \Core::$app->normalizePath( "$environmentName{$ds}views{$ds}layouts{$ds}" )
      ];

      return new $controllerNameSpace( $args );
    } else {
      call_user_func_array( [ $this, 'runError' ], [ "Controller {$controllerName} does not exist", 404 ] );
      exit();
    }
  }

  public function runError( $message = "", $code = 404 ){
    switch( $code ){
      case 404:
        header("HTTP/1.0 404 Not Found");
      break;
      case 405:
        header("HTTP/1.0 405 Not Allowed");
      break;
      default:
        header("HTTP/1.0 200 OK");
      break;
    }
    if( method_exists( $this, 'actionError' ) ){
      return call_user_func_array( [ $this, 'actionError' ], [ new \Exception( $message, $code ) ] );
    } else {
      echo "<pre>{$message}\nAlso missing default <strong>actionError</strong>.</pre>";
    }
  }

  public static function nameFromId( $string ){
    return str_replace( " ", "", ucwords( str_replace( "-", " ", str_replace( "_", " ", $string ) ) ) ) . "Controller";
  }
  public static function actionFromId( $string ){
    return "action" . str_replace( " ", "", ucwords( str_replace( "-", " ", str_replace( "_", " ", $string ) ) ) );
  }
  public static function getActionParams( $actionName, $params = [] ){
    var_dump( $actionName, $params );
  }
}
?>
