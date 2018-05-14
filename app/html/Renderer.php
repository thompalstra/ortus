<?php
namespace app\html;
class Renderer extends \app\core\Base{
  public function renderFile( $fp, $data = [], $output = true ){

    $extensions = [ ".php", ".html" ];

    $ds = \Core::$app->ds;
    $root = \Core::$app->root;

    if( $fp[0] == "/" || $fp[0] == "\\" ){
      $fp = substr( $fp, 1, strlen( $fp ) );
      $fp = "{$root}{$fp}";
    }

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
    echo "<pre>File does not exist: {$fp}.</pre>";
  }
}
?>
