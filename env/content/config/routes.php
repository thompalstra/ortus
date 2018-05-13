<?php
return [
  [ "\content\models\RouteComponent", [ "a" => "a", "b" => "b" ] ],
  "/" => "std/index",
  "/contact" => "company/pages/index",
  "/users/<username:^(.*)$>" => "std/user"
]
?>
