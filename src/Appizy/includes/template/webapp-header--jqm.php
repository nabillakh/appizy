<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Appizy">

    <title>Appizy</title>

    <?php if($libraries): ?>
    <?php foreach($libraries as $library) print $library; ?>
    <?php endif; ?>
    <?php if($style): ?>
      <style>
        <?php print $style; ?>
      </style>
    <?php endif; ?>

  </head>
  <body>