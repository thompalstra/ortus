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

define( "doc", document );

define( "app", function( querySelector ){
  return document.querySelectorAll( querySelector );
}, [ "$", "$$", "_", "test" ] );

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
  slideUp: function( speedInMs ){
    if( this.slideTimeout ){
      clearTimeout( this.slideTimeout );
    }

    var computed = window.getComputedStyle( this );
    var currentHeight = parseInt( computed["height"] );

    if( Number.isNaN( currentHeight ) ){ currentHeight = "0"; }

    this.style["height"] = currentHeight;
    this.style["overflow"] = "hidden";
    this.style["transition"] = speedInMs + "ms linear";
    this.style["height"] = "0";

    this.slideTimeout = setTimeout( function ( event ) {

      clearTimeout( this.slideTimeout );

      this.style["display"] = "none";
      this.style["height"] = "";
      this.style["transition"] = "";
      this.style["overflow"] = "";

    }.bind(this), speedInMs );
  },
  slideDown: function( speedInMs ){
    if( this.slideTimeout ){
      clearTimeout( this.slideTimeout );
    }

    var computed = window.getComputedStyle( this );
    var currentHeight = parseInt( computed["height"] );

    if( Number.isNaN( currentHeight ) ){ currentHeight = "0"; }

    this.style["display"] = "";
    this.style["height"] = "";
    this.style["transition"] = "";
    this.style["overflow"] = "";

    var realHeight = parseInt( computed["height"] );

    this.style["height"] = currentHeight;
    this.style["transition"] = speedInMs + "ms linear";
    this.style["overflow"] = "hidden";

    this.slideTimeout = setTimeout( function ( event ) {
      clearTimeout( this.slideTimeout );
      this.style["height"] = realHeight + "px";
    }.bind(this), 10 );
  },
  slideToggle: function( speedInMs ){
    if( parseInt( window.getComputedStyle( this )["height"] ) > 0 ){
      this.slideUp( speedInMs );
    } else {
      this.slideDown( speedInMs );
    }
  }
} );
extend( Document, Node ).with( {
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
  }
} );
