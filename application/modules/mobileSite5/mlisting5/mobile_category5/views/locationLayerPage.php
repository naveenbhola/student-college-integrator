<?php ob_start('compress'); 
$requestUrl = clone $request;
$requestUrl->setData(array('naukrilearning'=>0));		
$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
$headerComponent = array(
        'canonicalURL' => $canonicalurl
);

$this->load->view('/mcommon5/header',$headerComponent);

?>

<div id="wrapper" data-role="page" class="of-hide"  style="min-height: 413px;padding-top: 40px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	  echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
        <?php   $this->load->view('mobileCategoryHeader'); ?>
        
	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>        
        
    <input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
    <input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
    
	    
        <div data-role="content">
        <?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
		?>
	      <div data-enhance="false">
		
			<div style="display: none;">
			     <form action="/mcommon5/MobileSiteHome/browseInstitute" id="homepageForm" accept-charset="utf-8" method="post" enctype="multipart/form-data" novalidate="novalidate" >
				    <input name="categoryIdSelected" value="" type="hidden"/>
				    <input name="subcategoryIdSelected" value="" type="hidden"/>
				    <input name="locationIdSelected" value="" type="hidden"/>
				    <input name="locationTypeSelected" value="city" type="hidden"/>
				    <input name="countryIdSelected" value="2" type="hidden"/>
				    <input name="regionIdSelected" value="0" type="hidden"/>
				    <input name="isStudyAbroad" value="0" type="hidden"/>
			     </form>
			</div>
		
			<?php
			$this->load->view('/mcommon5/locationLayer');
			?>
            </div>
        </div>
</div>
<script>
var tabCategoryId = '<?=$categoryId?>';
var tabSubCategoryId = '<?=$subCategoryId?>';	
var pageName = '<?php echo $boomr_pageid;?>';
</script>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>