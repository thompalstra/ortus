<?php
class Application{

  public $config = [];

  public function __construct(){

    $root = $this->root = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR;
    $ds = $this->ds = DIRECTORY_SEPARATOR;

    include( "{$root}app{$ds}core{$ds}Core.php" );

    Core::$app = &$this;

    include( "{$root}app{$ds}core{$ds}autoload{$ds}base_autoload.php" );

    $this->config = include( "{$root}config.php" );

    $environmentClassName = $this->config["environment"]["className"];
    $controllerClassName = $this->config["controller"]["className"];
    $eventManagerClassName = $this->config["eventManager"]["className"];

    $this->environment = new $environmentClassName();
    $this->controller = new $controllerClassName();
    $this->eventManager = new $eventManagerClassName();
  }

  public function normalizePath( $path ){
    return str_replace( "/", DIRECTORY_SEPARATOR, str_replace( "\\", DIRECTORY_SEPARATOR, $path ) );
  }
  public function normalizeNamespace( $path ){
    return str_replace( "/", "\\", $path );
  }

  public function start(){

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
