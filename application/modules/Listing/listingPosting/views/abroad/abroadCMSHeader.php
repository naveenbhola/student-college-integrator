<?php

   $defaultJs              = array('common','ajax-api','studyAbroadCMS','user','CalendarPopup');
   $defaultCss             = array('common_new','abroad_cms','jquery.multiselect','jquery.multiselect.filter','jquery-ui','jquery.dataTables');
   $title                  = "SA CMS";
   $headerComponents       = array(
                                   'js'    =>  (count($extraJs) > 0 ?array_merge($defaultJs,$extraJs):$defaultJs),
                                   'css'   =>  (count($extraCss) > 0 ?array_merge($defaultCss,$extraCss):$defaultCss),
                                   'title' =>  $title
                               );
   
   $this->load->view('listingPosting/abroad/abroadCMSHeaderCommon', $headerComponents);
   $this->load->view('listingPosting/abroad/tooltipLoader');
   $this->load->view('common/calendardiv');
   
?>

<script>
   var formname = "<?php echo $formName; ?>";
</script>

<div class="abroad-cms-wrapper">
    
<div class='abroad-header'> <span>

  <?php $url = base64_encode($taburl);?>
  <?php if((isset($displayname))&& !empty($displayname))
{
   echo 'Hi '.$displayname; ?>
  &nbsp; <a onclick="SignOutUser();" id="signOutButton" href="#" >Sign out</a>
  <?php
}
?>

&nbsp;&nbsp;</span> </div>
<div class="clear_B"></div>
<div id="abroadCmsHeader">
  
  <div class="float_L"> <a href="/enterprise/Enterprise" style="text-decoration: none;"> <img src="/public/images/nshik_ShikshaLogo1.gif" alt="Shiksha Logo" style="margin-right: 5px; margin-left:10px" border="0">
    </a>
  </div>

  <div class="clear_L"></div>
</div>
<div class="tabBorder">&nbsp;</div>
 <div id="divtooltip" class="tool-tip" style="display: none;"></div>