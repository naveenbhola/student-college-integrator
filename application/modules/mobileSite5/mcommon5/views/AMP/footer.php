
  <?php 
  $this->load->view('mcommon5/AMP/footerLinks');
  loadBeaconTracker($beaconTrackData,true);
  ?>
  
   <div class="m-5top pad10 m-btm border-btm">
     <p class="t-cntr f12 color-0 font-w7">Partner Sites</p>
     <p class="m-top m-btm f12 color-3 t-cntr l-25">
       <a class="color-3 f12 m-lr7" href="http://www.naukri.com" target="_blank">Jobs</a>|
       <a class="color-3 f12 m-lr7" href="http://www.firstnaukri.com" target="_blank">Jobs for Freshers</a>|
       <a class="color-3 f12 m-lr7" href="http://www.naukrigulf.com" target="_blank">Jobs in Middle East</a>|
       <a class="color-3 f12 m-lr7" href="http://www.99acres.com" target="_blank">Real Estate</a>|
       <a class="color-3 f12 m-lr7" href="http://www.allcheckdeals.com" rel="nofollow" target="_blank">Real Estate Agents</a>|
       <a class="color-3 f12 m-lr7" href="http://www.jeevansathi.com" target="_blank">Matrimonial</a>|
       <a class="color-3 f12 m-lr7" href="http://www.policybazaar.com" rel="nofollow" target="_blank">Insurance Comparison</a>|
       <a class="color-3 f12 m-lr7" href="http://www.meritnation.com" rel="nofollow" target="_blank">School Online</a>|
       <a class="color-3 f12 m-lr7" href="http://www.brijj.com" rel="nofollow" target="_blank">Brij</a>|
       <a class="color-3 f12 m-lr7" href="http://www.zomato.com" rel="nofollow" target="_blank">Zomato</a>|
       <a class="color-3 f12 m-lr7" href="http://www.mydala.com/" rel="nofollow" target="_blank">mydala-Best deals in india</a>|
       <a class="color-3 f12 m-lr7" href="http://www.ambitionbox.com/" target="_blank">Ambition Box</a>
     </p>
   </div>
   <div class="pad10 pad-btm">
      <p class="color-3 f12 t-cntr"> Trade Marks belong to the respective owners.<br/>Copyright &copy; <?php echo date('Y'); ?> Infoedge India Ltd. All rights reserved
      </p>
   </div>   
   <amp-install-serviceworker src="/<?=getJSWithVersion('service-worker','pwa_mobile')?>" data-iframe-src="<?php echo SHIKSHA_HOME; ?>/loadsw"  layout="nodisplay">
  </amp-install-serviceworker>
</html>
