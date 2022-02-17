<!DOCTYPE html>
<html>
   <head>
      <title>Shiksha::Term and Condition</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <!-- <link href="/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
         <link href="/public/css/<?php echo getCSSWithVersion("static"); ?>" type="text/css" rel="stylesheet" /> -->
      <link rel="alternate" media="only screen and (max-width: 640px)" href="http://www.shiksha.com/mcommon/MobileSiteStatic/privacy" >
   </head>
   <body>
      <div id="headernoGradient">
         <div><img src="https://www.shiksha.com/public/images/nshik_ShikshaLogo1.gif" alt="Shiksha.com" class="header-img"/></div>
      </div>
      <!--Header Image Part-->

      <style>
         <?php $this->load->view('shikshaHelp/policyCSS',array());?>
      </style>
      <?php $this->load->view('shikshaHelp/termConditionContent',array()); ?>

      <div id="footerNav">
         <div align="center">
            <a href="javascript:" onClick="javascript:window.close();" class="bld">Close Window</a>
         </div>
         <div id="footerGradient" align="center">
            <p class="_copy">Copyright &copy; <?php echo date('Y') ?> Info Edge India Ltd. All rights reserved.</p>
         </div>
      </div>
      <script language="javascript" src="/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
   </body>
</html>
