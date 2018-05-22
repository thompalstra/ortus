<?php
namespace app\web;
class AssetManager extends \app\core\Base{

  static $assets = [];

  const POS_HEAD = "head";
  const POS_FOOTER ="footer";

  const TYPE_JS = "js";
  const TYPE_CSS = "css";

  public static function registerAssets( $assets ){
    if( isset( $assets[ "js" ] ) ){
      foreach( $assets[ "js" ] as $data ){
        self::registerJs( $data );
      }
    }
    if( isset( $assets[ "css" ] ) ){
      foreach( $assets[ "css" ] as $data ){
        self::registerCss( $data );
      }
    }
  }

  public static function registerJs( $data = [] ){
    self::registerFile( self::TYPE_JS, $data );
  }
  public static function registerCss( $data ){
    self::registerFile( self::TYPE_CSS, $data );
  }

  public static function registerFile( $type, $data ){
    if( !is_array( $data ) ){
      $data = [ $data, "position" => self::POS_FOOTER ];
    }

    if( !isset( self::$assets[ $data[ "position" ] ] ) ){
      self::$assets[ $data["position"] ] = [];
    }
    if( !isset( self::$assets[ $data[ "position" ] ][ $type ]  ) ){
      self::$assets[ $data[ "position" ] ][ $type ] = [];
    }
    self::$assets[ $data[ "position" ] ][ $type ][] = $data;
  }

  public static function depends( $asset ){
    usort( $asset, function ( $a, $b ){
      if( !isset( $b[ "depends" ] ) ){
        return 0;
      }

      if( $b[ "depends" ] == $a[0] ){
        return 1;
      }

      // return ( isset( $a[ "depends" ] ) && $a[ "depends" ] == $b[0] ) ? 1 : -1;
    } );
    return array_reverse( $asset );
  }

  public static function output( $type, $data ){
    return ( $type == self::TYPE_JS ) ?
      "<script src='{$data[0]}'></script>" :
      "<link href='{$data[0]}' rel='stylesheet'></link>";
  }

  public static function perf( $const, $output ){
    $html = [];
    if( isset( self::$assets[ $const ] ) ){
      foreach( self::$assets[ $const ] as $typeName => $type ){
        foreach( self::depends( $type ) as $data ){
          $html[] = self::output( $typeName, $data ) ;
        }
      }
    }
    $html = implode( $html );
    if ( $output ){
      return $html;
    } else {
      echo $html;
    }
  }

  public static function head( $output = false ){
    return self::perf( self::POS_HEAD, $output );
  }
  public static function footer( $output = false ){
    return self::perf( self::POS_FOOTER, $output );
  }
}
?>
