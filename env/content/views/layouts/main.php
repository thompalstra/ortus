<!DOCTYPE html>
<html>
  <?php head() ?>
  <body>
    <main id="main">
      <?=$content?>
    </main>
    <script>
      document.addEventListener( "app.initialized" , function(event){
        app.assets.add( "/common/js/app/app.core.js" );
        app.assets.add( "/common/js/app/app.req.js" );
      });
    </script>
    <?php footer() ?>
  </body>
</html>
