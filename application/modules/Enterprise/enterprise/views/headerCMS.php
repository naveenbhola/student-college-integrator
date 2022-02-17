<?php $this->load->view('enterprise/headerCMSCommon.php'); ?>

<div id="main-wrapper"><div id="content-wrapper">
<div class="wrapperFxd">
<noscript> <div class="jser"> <img style="vertical-align: middle;" src="https://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
<div id="headerGradienthome"> <span class="normaltxt_11p_blk darkgray disBlock txt_align_r">
  <?php $url = base64_encode($taburl);?>
  <?php if((isset($displayname))&& !empty($displayname))
{
   echo 'Hi '.$displayname; ?>
  &nbsp; <a onclick="SignOutUser();" href="#" >Sign out</a>
  <?php
}
?>

&nbsp;&nbsp;</span> </div>
<div class="clear_B"></div>
<div id="cmsHeader">
<?php if(($usergroup == "enterprise") && isset($myProducts)){ ?>
<?php
/*	$Gold = 0;$Silver = 0;$Bronze =0;
	$credits = 0;
	var_dump($myProducts);
	
	foreach ($myProducts as $product)
	{
		if ($product['BaseProdCategory']=="Listing")
		{
			$product['BaseProdSubCategory'] = $product['RemainingQuantity'];
			
		}
			
		if ($product['BaseProdCategory']=="Lead-Search")
		{
			$credits = $product['RemainingQuantity'];
		}	
	}
*/
//	$GoldSL = 0 ; $GoldML =0;$Bronze =0;
	$productNameCreditArr = array();
	
	$productNameCreditArr['Gold SL'] = 0;
	$productNameCreditArr['GOLD ML'] = 0;
	$productNameCreditArr['Bronze'] = 0;
	
	$credits = 0;

	foreach ($myProducts as $product){
		if ($product['BaseProdCategory']=="Listing")
		{
			$productNameCreditArr[$product['BaseProdSubCategory']] =$product['RemainingQuantity'] ;
		}
		if ($product['BaseProdCategory']=="Lead-Search")
		{
			$credits = $product['RemainingQuantity'];
		}	
	}
	
 
?>
       <div class="float_R mar_full_10p" style="text-align:right"> You have <?php echo $productNameCreditArr['GOLD ML'] ?><font class="bld" style="color:#C68E17"> Gold ML</font>, <?php echo $productNameCreditArr['Gold SL']; ?><font class="bld" style="color:#646D7E"> Gold SL</font> and <?php echo $productNameCreditArr['Bronze']; ?><font class="bld" style="color:#817339"> Bronze</font> Listings available in your account.
      
       <br/><font class="bld" style="color:#C68E17">  Credits available in your account</font> <?php echo $credits; ?>


    <?php if((isset($displayname))&& !empty($displayname)) { ?>
    <br/><br/> <a href="<?php echo SHIKSHA_HOME; ?>">Education Seekers click here</a>
    <?php } ?>
    </div>
	 <div class="float_R">
  </div>
  <?php } ?>
  <?php if(isset($bannerProperties)){ ?>
  <div class="float_R">
    <?php
        $bannerProperties = isset($bannerProperties) ? $bannerProperties : array('pageId'=>'ENTERPRISE_HOME', 'pageZone'=>'TOP'); 
        $this->load->view('common/banner',$bannerProperties); 
    ?>
  </div>
  <?php } ?>
  
  <div class="float_L"> <a href="/enterprise/Enterprise" style="text-decoration: none;"> <img src="<?php echo MEDIA_SERVER;?>/public/images/nshik_ShikshaLogo1.gif" alt="Shiksha Logo" style="margin-right: 5px; margin-left:10px" border="0">
    <!--<span style="position:relative;top:-14px;left:5px;font-size: 16px;font-weight:bold;color:#4d4948;text-transform:capitalize;" ><?php echo $usergroup;?></span>-->
    </a>
  </div>

  <div class="clear_L"></div>
</div>
<?php
	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
?>
<input type="hidden" value="<?php echo $userid;?>" name="effectiveUserId" id="effectiveUserId">
<input type="hidden" value="<?php echo $usergroup;?>" name="effectiveUserGroup" id="effectiveUserGroup">
