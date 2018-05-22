<?php
use app\web\AssetManager;
$app = \Core::$app;

function head(){
  return render_partial( "/env/content/views/layouts/head.php" );
}
function footer(){
  return render_partial( "/env/content/views/layouts/footer.php");
}

AssetManager::registerAssets( [
  "css" => [
    [ "/common/css/style.css", "position" => AssetManager::POS_HEAD ],
    [ "/css/style.css", "position" => AssetManager::POS_HEAD, "depends" => "/common/css/style.css" ]
  ],
  "js" => [
    [ "/common/js/app/app.core.js", "position" => AssetManager::POS_FOOTER ],
    [ "/common/js/app/app.req.js", "position" => AssetManager::POS_FOOTER, "depends" => "/common/js/app/app.core.js" ],
    [ "/common/js/script.js", "position" => AssetManager::POS_FOOTER, "depends" => "/common/js/app/app.req.js" ],
    [ "/js/script.js", "position" => AssetManager::POS_FOOTER, "depends" => "/common/js/script.js" ],
  ]
] );
