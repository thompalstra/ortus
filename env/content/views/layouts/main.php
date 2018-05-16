<!DOCTYPE html>
<html>
  <?=get_partial( "partials/head", [], true )?>
  <body>
      <main
        id="main"
        role="main"
        data-layout="<?=\Core::$app->controller->layout?>"
        data-action="<?=\Core::$app->controller->actionId?>">
        <div class="grid main">
          <section class="header">
            header
          </section>
          <nav class="nav">
            nav
          </nav>
          <aside class="asideleft">
            asideleft
          </aside>
          <section class="content">
            <ul class="content breadcrumbs">
              <li>Home</li>
              <li>Products</li>
              <li>Samsung UE55MU7000</li>
            </ul>
            <div class="cs content main">
              <?=$content?>
              test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>
              test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>test <br/>
            </div>
          </section>
          <aside class="asideright">
            <div class="grid widgets">
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
              <div class="widget">
                widget
              </div>
            </div>
          </aside>
          <footer class="footer grid">
            <ul class="pages">
              <li>Product categories</li>
              <li>Shipping</li>
            </ul>
            <ul class="social">
              <li>Facebook</li>
              <li>Twitter</li>
              <li>LinkedIn</li>
            </ul>
            <ul class="links">
              <li>Contact</li>
              <li>About</li>
              <li>Newsletter</li>
            </ul>
            <div class="contact">
              <form method="POST">
                <label>
                  Keep me up to date
                  <input type="email"/>
                </label>
              </form>
            </div>
          </footer>
        </div>
      </main>
    <script src="/common/js/script.js"></script>
    <script src="/js/script.js"></script>
    <script src="/common/js/app/app.core.js"></script>
  </body>
</html>
