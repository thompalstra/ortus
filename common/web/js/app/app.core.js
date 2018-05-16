console.log( "initializing app..." );
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

define( "app", function( querySelector ){
  return document.querySelectorAll( querySelector );
}, [ "$", "$$", "_", "test" ] );

app.extend = function( list ){
  return {
    list: list,
    with: function( ext, forceProperty ){
      for( let i = 0; i < this.list.length; i++){
        var target;
        if( ( typeof this.list[i].prototype !== "undefined" && forceProperty != true ) ){
          target = this.list[i].prototype;
          console.log( this.list[i], "is a prototype" );
        } else {
          target = this.list[i];
          console.log( this.list[i], "is an object" );
        }

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

var derp = {};

extend( app, derp ).with( {
  post: function( url, data ){
    return fetch( url, {
      method: "POST",
      body: JSON.stringify( data )
    } );
  }
} );
extend( Node ).with( {
  on: function( eventTypes, b, c, d ){
    eventTypes.split( " " ).forEach( ( eventType ) => {

    } );
  }
} );
extend( NodeList, HTMLCollection ).with( {
  forEach: function( callable ){
    for( var i = 0; i < this.length; i++ ){
      callable.call( window, this[i] );
    }
  }
} );
