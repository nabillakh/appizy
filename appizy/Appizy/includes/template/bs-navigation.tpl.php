<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#<?php print $navid; ?>">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a id="top" class="navbar-brand" href="#<?php print $front_page; ?>"><?php print $title; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="<?php print $navid; ?>">
      <ul class="nav navbar-nav">
        <?php foreach ($sections as $anchor => $title): ?>
          <li><a href="#<?php print $anchor; ?>" ><?php print $title; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>