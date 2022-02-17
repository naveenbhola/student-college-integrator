<!DOCTYPE html>
<html lang="en">
  <head>
  <base href="/">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Shiksha Admin | </title>

    <?php $this->load->view('enterprise/adminBase/adminCss') ?>

   
  </head>
  <body class="nav-md">
    <script>
        <?php addHtmlVersionVariables(); ?>
    </script>
    <enterprise-app>Loading...</enterprise-app>
    <?php //$this->load->view('enterprise/adminBase/adminJs') ?>
    <script language = "javascript" src = "/public/js/tinymce3/jscripts/tiny_mce/tiny_mce.js"></script>
    <?php 
      if(ENVIRONMENT == 'development'){?>
        <script src="//<?php echo JSURL; ?>/public/angular/node_modules/core-js/client/shim.min.js"></script>
        <script src="//<?php echo JSURL; ?>/public/angular/node_modules/zone.js/dist/zone.js"></script>
        <script src="//<?php echo JSURL; ?>/public/angular/node_modules/reflect-metadata/Reflect.js"></script>
        <script src="//<?php echo JSURL; ?>/public/angular/node_modules/systemjs/dist/system.src.js"> </script>
        <script src="//<?php echo JSURL; ?>/public/angular/systemjs.config.js"> </script>
        <script src="//<?php echo JSURL; ?>/public/angular/node_modules/ng2-bootstrap/bundles/ng2-bootstrap.min.js" ></script>
        <script>System.import('app').catch(function(err){ console.log(err); });</script>
        <?php }else{ ?>
          <script src="//<?php echo JSURL; ?>/public/angular/node_modules/systemjs/dist/system.src.js"></script> 
          <script src="//<?php echo JSURL; ?>/public/angular/node_modules/ng2-bootstrap/bundles/ng2-bootstrap.min.js" ></script>
          <script src="/public/angular/dist/main.bundle.js?v=<?php echo getJSWithVersion('angularBundle','abroadMobile1'); ?>" async defer></script>            
        <?php } ?>
  </body>
</html>

          