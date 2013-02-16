<!-- Part 1: Wrap all page content here -->
<div id="wrap">

  <?= $this->template->nav->default_view('layout/_nav') ?>

  <!-- Begin page content -->
  <div class="container">
    <div class="page-header">
      <h1>Sticky footer with fixed navbar</h1>
    </div>
    <p class="lead">Pin a fixed-height footer to the bottom of the viewport in desktop browsers with this custom HTML and CSS. A fixed navbar has been added within <code>#wrap</code> with <code>padding-top: 60px;</code> on the <code>.container</code>.</p>
    <p>Back to <a href="./sticky-footer.html">the sticky footer</a> minus the navbar.</p>
  </div>

  <div id="push"></div>

</div>

<?= $this->template->footer->set_default_view('layout/_footer') ?>