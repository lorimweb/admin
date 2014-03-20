<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $descricao; ?>">
    <meta name="author" content="GG2 - Gihovani Filipp Pereira Demétrio">
    <link rel="shortcut icon" href="<?php echo site_url('favicon.ico'); ?>">

    <title><?php echo $titulo; ?></title>

    <!-- Bootstrap core CSS -->
    <?php if (count($css)): ?>
    <?php foreach ($css as $v): ?>
      <link rel="stylesheet" href="<?php echo $v; ?>" type="text/css" />
    <?php endforeach; ?>
    <?php endif; ?>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="http://getbootstrap.com/docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-46342551-1', 'gg2.com.br');
      ga('send', 'pageview');

    </script>
  </head>

  <body>