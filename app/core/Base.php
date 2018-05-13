<?php
namespace app\core;
class Base{
  public function __construct( $options = [] ){
    foreach( $options as $k => $v ){
      $this->$k = $v;
    }
  }
}
?>
