<?php
namespace MyCompany\MyPlugin;
class Events{
  public static $events = [
    "beforeParse" => [
      "beforeParse"
    ],
    "afterParse" => [
      "afterParse"
    ],
    "beforeHandle" => [
      "beforeHandle"
    ],
    "afterHandle" => [
      "afterHandle"
    ]
  ];
  public static function beforeParse( $event ){}
  public static function afterParse( $event ){}
  public static function beforeHandle( $event ){}
  public static function afterHandle( $event ){}
}
?>
