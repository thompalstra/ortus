<!DOCTYPE html>
<html>
  <?=get_partial( "partials/head", [], true )?>
  <body>
    <div class="wrap">
      <div class="grid home">
        <nav class="grid-module nav">
          nav
        </nav>
        <div class="sidebar left">
          <ul class="grid-module sidebar-links">
            <li>
              <a href="#">Link</a>
            </li>
            <li>
              <a href="#">Link</a>
            </li>
            <li>
              <a href="#">Link</a>
            </li>
            <li>
              <a href="#">Link</a>
            </li>
          </ul>
        </div>
        <div class="content">
          <?=$content?>
        </div>
        <div class="sidebar right">
          <div class='grid-module widget'>
            widget
          </div>
          <div class='grid-module widget'>
            widget
          </div>
          <div class='grid-module widget'>
            widget
          </div>
          <div class='grid-module widget'>
            widget
          </div>
        </div>
        <footer class="footer">
          footer
        </footer>
      </div>
    </div>
    <script src="/common/js/script.js"></script>
    <script src="/js/script.js"></script>
  </body>
</html>
