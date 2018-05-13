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
    echo "actionIndex";
  }
  public function actionUser( $username ){
    if( in_array( $username, [ "admin", "fred", "bob" ] ) ){
      echo "<h2>Welcome {$username}</h2>";
    } else {
      return $this->runError( "User not found" );
    }

  }
}
?>
