<?php
return [
  "requestParser" => [
    "className" => "\app\web\RequestParser"
  ],
  "requestHandler" => [
    "className" => "\app\web\RequestHandler"
  ],
  "eventManager" => [
    "className" => "\app\web\EventManager"
  ],
  "environment" => [
    "className" => "\app\web\Environment",
    "default" => "content"
  ],
  "controller" => [
    "className" => "\app\web\Controller",
    "default" => "std",
    "actionDefault" => "index",
    "actionError" => "error",
    "layout" => "main"
  ],
  "renderer" => [
    "className" => "\app\html\Renderer"
  ]
];
?>
