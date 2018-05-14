<?php
function add_hook( $name, $callable ){
  \Core::$app->hooks[ $name ] = $callable;
}

function hook( $name, $arguments = [] ){
  if( isset( \Core::$app->hooks[$name] ) ){
    return call_user_func_array( \Core::$app->hooks[$name], $arguments );
  } else {
    echo "<strong>Missing hook: <em>`$name`</em></strong>";
  }
}
add_hook( "get_partial", function( $fp, $data = [], $output = true ){
    $root = \Core::$app->root;
    $ds = \Core::$app->ds;
    $extensions = [ ".php", ".html" ];
    if( $fp[0] == "/" || $fp[0] == "\\" ){
      $sourcePath = "";
    } else {
      $traceFile = null;
      foreach( debug_backtrace() as $debug_trace ){
        if( isset( $debug_trace["file"] ) && $debug_trace["function"] == "get_partial" ){
          $traceFile = $debug_trace["file"];
          break;
        }
      }
      $sourcePath = dirname( $traceFile );
      $sourcePath = str_replace( \Core::$app->root, "", $sourcePath ) . $ds;
    }
    $fp = \Core::$app->normalizePath( "{$root}{$sourcePath}{$fp}" );
    foreach( $extensions as $extension ){
      if( file_exists( "{$fp}{$extension}" ) ){
        extract($data, EXTR_PREFIX_SAME, 'data');
        ob_start();
        require( "{$fp}{$extension}" );
        $content = ob_get_contents();
        ob_end_clean();
        if( $output ){
          return $content;
        } else {
          echo $content;
          break;
        }
      }
    }
} );
function get_partial( $fp, $data = [], $output = true ){
  return hook( "get_partial", [ $fp, $data, $output ] );
}
add_hook( "get_title", function(){
  return \Core::$app->params["title"];
} );
function get_title(){
  return hook( "get_title" );
}
add_hook( "set_title", function( $value ){
  \Core::$app->params["title"] = $value;
} );
function set_title( $value ){
  return hook( "set_title", [ $value ] );
}
