<?php
namespace app\web;
class RequestHandler extends \app\core\Base{
  public function handle( $route ){
    $controllerClassName = \Core::$app->config["controller"]["className"];
    \Core::$app->controller = \Core::$app->controller->fromRoute( $route );
    return call_user_func_array( [ \Core::$app->controller, 'runAction' ], [ \Core::$app->controller->actionId, $route[1] ] ) ;
  }
}
?>
