<div class="clearfix"></div>
<footer <?=$hideHTML == "true"?"style='display:none;'":""?> id="footer" class="main-footer">
<div class="footerTop-div" id="footerBack2Top">
  <a href="javascript:void(0);"><i class="foot-arrUp"></i>Top of Page</a>
</div>
<?php 
if(isset($saFooterNavigationHTML)){
  echo $saFooterNavigationHTML;
}else{
  $footerNav = $this->load->view('studyAbroadCommon/footerNavigation',array(),true);
  $footerNavigationContent = sanitize_output($footerNav);
  echo $footerNavigationContent;
  $fp=fopen($footerNavigationCache,'w+');
  flock( $fp, LOCK_EX ); // exclusive lock
  fputs($fp,$footerNavigationContent);
  flock( $fp, LOCK_UN ); // release the lock
  fclose($fp);
}
?>
</footer>
<div class="clearfix"></div>