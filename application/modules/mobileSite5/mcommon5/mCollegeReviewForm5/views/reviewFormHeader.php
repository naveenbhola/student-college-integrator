<!-- Show the Category page Header -->    
<header id="page-header" class="header" data-role="header" data-tap-toggle="false">
 <?php if($letsIntern != ''){?>
<div data-enhance="false" class="header">	
    <div class="logo" style="padding-bottom:5px;">
    <span style="vertical-align: middle;" ><img border="0" title="shiksha.com" alt="shiksha.com" src="/public/mobile5/images/mobile_logo1.png" style="vertical-align: middle;"></span>
    <span style="height:20px !important;margin:0 5px;vertical-align: middle; color:#393939;">&</span>
    <span style="vertical-align: middle;"><img border="0" title="Letsintern.com" alt="Letsintern.com" src="/public/images/campusAmbassador/letsintern-logo.jpg" style="vertical-align: middle;"></span>
    <span  style="margin:0 5px;vertical-align:middle;color:#393939;">Initiative</span></div>
</div>

 <?php } else {?>
<div data-enhance="false" class="header">	
    <a class="logo"><span class="msprite" style="cursor: pointer;" onclick="window.location='<?php echo SHIKSHA_HOME;?>';trackEventByGAMobile('MOBILE_HEADER_LOGO_FROM_<?php echo strtoupper($boomr_pageid);?>');">Shiksha.com</span></a>
</div>
<?php } ?>
</header>    
<!-- End the Header for Category page -->