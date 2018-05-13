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
  public static function beforeParse( $event ){
    // echo 'before...';
    // $event->preventDefault();
  }
  public static function afterParse( $event ){
    // echo 'after';
  }
}
?>
