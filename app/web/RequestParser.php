<?php
namespace app\web;
class RequestParser extends \app\core\Base{
  public function parse(){
    $uri = $_SERVER["REQUEST_URI"];
    $params = $_GET;
    if( strpos( $uri, "?" ) !== false ){
      $uri = substr( $uri, 0, strpos( $uri, "?" ) );
    }
    foreach( $this->getRoutes() as $match => $target ){
      if( ( $route = $this->matchRoute( $match, $target, $uri ) ) !== false ){
        return $route;
      }
    }
    return [ $uri, $params ];
  }

  public function getRoutes(){

    $root = \Core::$app->root;
    $ds = \Core::$app->ds;
    $environmentName = \Core::$app->environment->name;
    $environmentDirectory = \Core::$app->environment->directory;

    $routes = [];

    $environmentRoutesFilePath = "{$root}{$environmentDirectory}config{$ds}routes.php";

    if( file_exists( "{$root}{$environmentDirectory}config{$ds}routes.php" ) ){
      $routes = array_merge( $routes, include( "{$root}{$environmentDirectory}config{$ds}routes.php" ) );
    }

    foreach( scandir( "{$root}plugins" ) as $companyName ){
      if( $companyName == "." || $companyName == ".." ){ continue; }
      foreach( scandir( "{$root}plugins{$ds}{$companyName}" ) as $pluginName ){
        if( $pluginName == "." || $pluginName == ".." ){ continue; }
        if( file_exists( "{$root}plugins{$ds}{$companyName}{$ds}{$pluginName}{$ds}config{$ds}routes.php" ) ){
          $pluginRoutes = include( "{$root}plugins{$ds}{$companyName}{$ds}{$pluginName}{$ds}config{$ds}routes.php" );
          if( isset( $pluginRoutes[ 0 ] ) ) {
            $routes = array_merge( $routes, $pluginRoutes[ 0 ] );
          }
          if( isset( $pluginRoutes[ $environmentName ] ) ){
            $routes = array_merge( $routes, $pluginRoutes[ $environmentName ] );
          }
        }
      }
    }
    return $routes;
  }

  public function matchRoute( $match, $target, $uri ){
    if( is_array( $target ) ){
      // run component
      reset($target);
      $componentClass = $target[0];
      $arguments = $target[1];
      if( class_exists( $componentClass ) ){
        $route = call_user_func_array( [ $componentClass, 'parse' ], [ $uri, $arguments ] );
        if( !empty( $route ) ){
          return $route;
        }
      }
    } else {
      // run string match and regex match
      $match = trim( $match, "/" );
      $uri = trim( $uri, "/" );
      $matchParts = explode("/", $match);
      $uriParts = explode("/", $uri);
      $score = 0;
      $requiredScore = count( $uriParts );
      $params = [];
      if( count( $matchParts ) == count( $uriParts ) ){
        foreach( $matchParts as $matchIndex => $matchPart ){
          if( isset( $matchParts[$matchIndex][0] ) && $matchParts[$matchIndex][0] == '<' ){
            $filter = explode(":", trim( trim( $matchParts[$matchIndex], "<" ), ">" ) );
            if( preg_match(  "/{$filter[1]}/", $uriParts[$matchIndex] ) ){
              $params[$filter[0]] = $uriParts[$matchIndex];
              $score++;
            }
          } else if( $matchParts[$matchIndex] == $uriParts[$matchIndex] ){
            $score++;
          }
        }
      }
      if( $score == $requiredScore ){
        $_GET = $_GET + $params;
        return [ $target, $params ];
      }
    }
    return false;
  }
}
?>
