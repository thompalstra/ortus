<?php
namespace content\controllers;
class StdController extends \app\web\Controller{
  public function beforeAction( $actionId ){
    return true;
  }
  public function afterAction( $actionId ){
    return true;
  }
  public function actionIndex(){
    set_title( "Welcome to Ortus" );
    return $this->render( "index" );
  }
}
?>
