<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Appizy">

    <title>Appizy</title>

    <!-- Bootstrap core CSS -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Bootstrap core JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <?php if($libraries): ?>
    <?php foreach($libraries as $library) print $library; ?>
    <?php endif; ?>
    
  </head>

  <body>

    <?php if($alerts): ?>
    <div class="container">
      <div class="alert alert-danger nodl" style="display: none">
        <h4>Oops! You got an error.</h4>
        <p>Your file might not work perfectly. <strong>Please make sure you use the "Download" button to get your application.</strong> If the errors remains please contact Appizy team.</p>
      </div>
    </div>
    <?php endif; ?>
    <div class="container">
      <?php if($style): ?>
      <style>
        <?php print $style; ?>
      </style>
      <?php endif; ?>

      <?php if($content) print $content; ?>

      <?php if($script) : ?>
      <script type="text/javascript">
        <?php print $script; ?>
      </script>
      <?php endif; ?>
    </div><!-- /.container -->
  </body>
</html>
