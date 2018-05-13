<?php
namespace app\web;
class Event extends \app\core\Base{
  public $defaultPrevented = false;
  public function __construct( $type, $params = [] ){
    $this->type = $type;

    foreach( $params as $k => $v ){
      $this->$k = $v;
    }
  }
  public function preventDefault(){
    $this->defaultPrevented = true;
  }
}
?>
