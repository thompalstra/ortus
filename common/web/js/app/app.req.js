extend( app ).with( {
  post: function( url, data ){
    data.method = "POST";
    data.body = ( data.body ) ? app.serialize( data.body ) : "";

    data.headers = ( data.headers ) ? data.headers : {};
    data.headers["Content-Type"] = "application/x-www-form-urlencoded";

    return fetch( url, data )
      .catch( ( err ) => { console.warn( "Error: " + err ) } );
  },
  get: function( url, data ){
    if( data.body ){
      url = url + "?" + app.serialize( data.body );
    }
    data.method = "GET";

    return fetch( url, data )
      .catch( ( err ) => { console.warn( "Error: " + err ) } );
  },
  json: function( url, data ){

  }
}, true );
