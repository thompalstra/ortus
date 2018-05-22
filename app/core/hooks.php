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
add_hook( "render_partial", function( $fp, $data = [], $output = false ){
  $root = \Core::$app->root;
  $ds = \Core::$app->ds;

  $extensions = [ ".php", ".html" ];
    $sourcePath = "";

  if( $fp[0] == "/" || $fp[0] == "\\" ){
    $fp = substr( $fp, 1, strlen( $fp ) );
  } else {
    foreach( debug_backtrace() as $debug_trace ){
      if( isset( $debug_trace["file"] ) && $debug_trace["function"] == "render_partial" ){
        $sourcePath = str_replace( \Core::$app->root, "", dirname( $debug_trace["file"] ) ) . $ds;
        break;
      }
    }
  }
  $fp = \Core::$app->normalizePath( "{$root}{$sourcePath}{$fp}" );

  if( !isset( pathinfo( $fp )["extension"] ) ){
    foreach( $extensions as $extension ){
      if( file_exists( "{$fp}{$extension}" ) ){
        $fp = "{$fp}{$extension}";
        break;
      }
    }
  }
  if( file_exists( "{$fp}" ) ){
    extract($data, EXTR_PREFIX_SAME, 'data');
    ob_start();
    require( "{$fp}" );
    $content = ob_get_contents();
    ob_end_clean();
    if( $output ){
      return $content;
    } else {
      echo $content;
    }
  }
} );
function render_partial( $fp, $data = [], $output = false ){
  return hook( "render_partial", [ $fp, $data, $output ] );
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
