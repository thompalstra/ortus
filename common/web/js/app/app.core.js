let define = function( name, object, aliases ){
  try {
    window[name] = object;
    if( Array.isArray( aliases ) ){
      for( let i = 0; i < aliases.length; i++ ){
        window[ aliases[i] ] = object;
      }
    }
  } catch (err) { console.warn( err ); }
}

define( "doc", document );

define( "app", function( querySelector ){
   return document.querySelectorAll( querySelector );
}, [ "_" ] );

app.extend = function( list ){
  return {
    list: list,
    with: function( ext, forceProperty ){
      for( let i = 0; i < this.list.length; i++){
        var target = ( typeof this.list[i].prototype !== "undefined" && forceProperty != true ) ? this.list[i].prototype : this.list[i];
        for( let i in ext ){
          target[ i ] = ext[i];
        }
      }
    }
  }
}

define( "extend", function(){
  return app.extend( arguments );
} );

extend( app ).with( {
  serialize: function( obj, prefix ){
    var output = [];
    for ( objIndex in obj ) {
      var key = ( prefix ) ? prefix + "[" + objIndex + "]" : objIndex;

      if( typeof obj[ objIndex ] === "object" ){
        output.push( app.serialize( obj[ objIndex ], key ) );
      } else {
        output.push( encodeURIComponent( key ) + "=" + encodeURIComponent( obj[ objIndex ] ) );
      }
    }
    return output.join("&");
  },
  assets: {
    _ready: [],
    ready: function( name ){
      if( ( index = app.assets._unready.indexOf( name ) ) > -1 ){
        app.assets._unready.splice( index, 1 );
      }
      app.assets._ready.push( name );
    },
    _unready: [],
    add: function( name ){
      app.assets._unready.push( name );
    }
  },
  ready: function( ){
    for( let index in app.assets._unready ){
      var assets = app.assets._unready[ index ];
      var script = document.one( '[src="'+assets+'"]' );
      if( document.one( '[src="'+assets+'"]' ) ){
        app.assets._ready.push( assets );
        delete app.assets._unready[ index ];
      }
    }

    console.group( "assets" );
      console.group( "ready" );
        for( var i in app.assets._ready ) {
          console.log( app.assets._ready[i] );
        }
      console.groupEnd();
      console.group( "unready" );
        for( var i in app.assets._unready ) {
    	     console.log( app.assets._unready[i] );
         }
      console.groupEnd();
    console.groupEnd();

    document.do( "ready" );
  }
}, true );

extend( Document, Node ).with( {
  on: function( eventTypes, b, c, d ){
    eventTypes.split( " " ).forEach( ( eventType ) => {
      if( typeof b == "function" ){
        this.addEventListener( eventType, b );
      } else if( typeof b == "string" && typeof c == "function" ){
        document.addEventListener( eventType, function( originalEvent ) {
          if( originalEvent.target.matches( b ) ){
            c.call( originalEvent.target, originalEvent )
          } else if( closest = originalEvent.target.closest( b ) ){
            c.call( closest, originalEvent );
          }
        } );
      }
    } );
  },
  do: function( eventType, attr ){

    if( typeof attr === "undefined" ){
      attr = true;
    }

    if( typeof attr === "boolean"  ){
      attr = {
        cancelable: attr,
        bubbles: attr,
      }
    }

    var event = new CustomEvent( eventType, attr );
  },
  one: function( querySelector ){
    return this.querySelector( querySelector );
  },
  find: function( querySelector ){
    return this.querySelectorAll( querySelector );
  }
} );
extend( NodeList, HTMLCollection ).with( {
  forEach: function( callable ){
    for( var i = 0; i < this.length; i++ ){
      callable.call( window, this[i] );
    }
  },
  delegate: function( name, arg ){
    for( let i = 0; i < this.length; i++ ){
      this[i][name].apply( this[i], arg );
    }
  },
  on: function(){
    this.delegate( "on", arguments );
  }
} );

document.dispatchEvent( new CustomEvent( "app.initialized", { cancelable: true, bubbles: true } ) );

document.addEventListener( "DOMContentLoaded", function( event ) {
  app.ready();
} );
