<html>
  <head>
    <title>Angular 2 QuickStart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 1. Load libraries -->
     <!-- Polyfill(s) for older browsers -->
    <script src="//<?php echo JSURL; ?>/public/node_modules/core-js/client/shim.min.js"></script>
    <script src="//<?php echo JSURL; ?>/public/node_modules/zone.js/dist/zone.js"></script>
    <script src="//<?php echo JSURL; ?>/public/node_modules/reflect-metadata/Reflect.js"></script>
    <script src="//<?php echo JSURL; ?>/public/node_modules/systemjs/dist/system.src.js"></script>
    <!-- 2. Configure SystemJS -->
    <script src="//<?php echo JSURL; ?>/public/systemjs.config.js"></script>
    <link rel="stylesheet" href="//<?php echo JSURL; ?>/public/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script>
      System.import('app').catch(function(err){ console.log(err); });
    </script>
  </head>
  <!-- 3. Display the application -->
  <body>
    <my-app>Loading...</my-app>
  </body>
</html>
