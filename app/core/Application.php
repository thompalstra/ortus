<?php
class Application{

  public $config = [];
  public $params = [
    "title" => "My website",
    "assets" => [
      "head" => [],
      "footer" => []
    ]
  ];

  public function __construct(){
    
    $root = $this->root = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR;
    $ds = $this->ds = DIRECTORY_SEPARATOR;

    include( "{$root}app{$ds}core{$ds}Core.php" );

    Core::$app = &$this;

    include( "{$root}app{$ds}core{$ds}autoload{$ds}base_autoload.php" );

    if( file_exists( "{$root}config.php" ) ){
      $this->config = include( "{$root}config.php" );
    }

    if( file_exists( "{$root}functions.php" ) ){
      include( "{$root}functions.php" );
    }

    if( file_exists( "{$root}app{$ds}core{$ds}hooks.php" ) ){
      include( "{$root}app{$ds}core{$ds}hooks.php" );
    }

    $environmentClassName = $this->config["environment"]["className"];
    $controllerClassName = $this->config["controller"]["className"];
    $eventManagerClassName = $this->config["eventManager"]["className"];
    $rendererClassName = $this->config["renderer"]["className"];

    $this->environment = new $environmentClassName();
    $this->controller = new $controllerClassName();
    $this->eventManager = new $eventManagerClassName();
    $this->renderer = new $rendererClassName();

    $environmentDirectory = $this->environment->directory;

    if( file_exists( "{$root}{$environmentDirectory}config.php" ) ){
      foreach( include( "{$root}{$environmentDirectory}config.php" ) as $k => $v ){
        $this->config[$k] = $v;
      }
    }

    if( file_exists( "{$root}{$environmentDirectory}functions.php" ) ){
      include( "{$root}{$environmentDirectory}functions.php" );
    }


  }

  public function normalizePath( $path ){
    return str_replace( "/", DIRECTORY_SEPARATOR, str_replace( "\\", DIRECTORY_SEPARATOR, $path ) );
  }
  public function normalizeNamespace( $path ){
    return str_replace( "/", "\\", $path );
  }

  public function parseRequest(){
    $this->eventManager->dispatchEvent( new \app\web\Event( "beforeParse", [] ) );

    $requestParserClassName = $this->config["requestParser"]["className"];
    $requestParser = new $requestParserClassName();

    $route = call_user_func_array( [ $requestParser, "parse" ], [] );

    $this->eventManager->dispatchEvent( new \app\web\Event( "afterParse", [] ) );

    return $route;
  }
  public function handleRequest( $route ){
    $this->eventManager->dispatchEvent( new \app\web\Event( "beforeHandle", [] ) );

    $requestHandlerClassName = $this->config["requestHandler"]["className"];
    $requestHandler = new $requestHandlerClassName();
    call_user_func_array( [ $requestHandler, "handle" ], [ $route ] );

    $this->eventManager->dispatchEvent( new \app\web\Event( "afterHandle", [] ) );
  }
}


?>
