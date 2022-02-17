<?php ob_start('compress'); ?>
<?php
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
?>

<?php
 /*$headerComponents = array(
		'mobilecss'   =>      array('collegePredictorV2'),
		'jsMobile'		=> array('collegePredictorNM'),
		'title' =>      $m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>'collegePredictorV2',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);*/
 $headerComponents = array(
  'm_meta_title'=>$m_meta_title,
  'm_meta_description'=>$m_meta_description,
  'm_meta_keywords'=>$metaKeywords,
  'canonicalURL' => $canonicalURL,
  'jsMobile' => array(),
  'mobilePageName' => 'mRankingPage',
  'noJqueryMobile' => 1,
  'noIndexNoFollow' => 1
);

 // $this->load->view('/mcommon5/header',$headerComponents);
 $this->load->view('/mcommon5/headerV2',$headerComponents);
?>
<style type="text/css">
<?php $this->load->view('V2/collegePredictorCss',$headerComponents); ?>
</style>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	  echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    
        <?php   $this->load->view('V2/collegePredictorHeader'); ?>
        		<h1 style=" display:none;text-align:left;padding: 0.2em 0" title="<?=$heading?>">
			   <?php echo $heading;?>
		       </h1>

	<div data-role="content">
		<?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
		<?php
			if($defaultView) {
				$this->load->view('V2/collegePredictorWelcome'); 
			}
		?>
		<div data-enhance="false">
				<?php
				if (getTempUserData('confirmation_message')){?>
				<section class="top-msg-row"  id="successMsgSection">
					<div class="thnx-msg">
				    	<i class="icon-tick"></i>
						<p>
						<?php echo getTempUserData('confirmation_message'); ?>
						</p>
					</div>
					<div style="clear:both"></div>
				</section>
				<?php } ?>
				<?php
				   deleteTempUserData('confirmation_message');
				   deleteTempUserData('confirmation_message_ins_page');
				   deleteTempUserData('collegepredictor_email_link');
				?>
			<div class="pre-launch-bg">
				<?php 
					if($defaultView) {
						$this->load->view('V2/collegePredictorSearch'); 
					}
					?>
			</div>
				<div id="searchResults">
					<?php if(!$defaultView) {
							$this->load->view('V2/collegePredictorList1'); 
						}
					?>
				</div>
		</div>
		<!-- Loading Div -->
		<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>	

		<div data-enhance="false">
			<?php $this->load->view('/mcommon5/footerLinksV2',array( 'jsFooter'=> array('collegePredictorNM'),'cssFooter'=>array('mcommon') )); ?>
		</div>
	</div>		
</div>
<div id="popupBasicBack" data-enhance='false'></div> 
<?php $this->load->view('/mcommon5/footerV2'); ?>

<?php if(!$defaultView) { ?>
	<input style="display: none;" type="radio" checked="" name='rank_type' id="quota_1" value='<?=$inputData['rankType']?>'>
	<select name="userCategory" id="userCategory"  style="display:none;" >
		<optgroup>
			<?php foreach($categoryData as $key=>$value){ ?>
		    	<option value="<?php echo $key;?>" <?php if(!empty($inputData['categoryName']) &&  $inputData['categoryName'] ==  $key) {echo 'selected';}?> ><?php echo $value;?></option>
			<?php } ?>
		</optgroup>
	</select>
<?php 
} ?>
<div  id="searchFilters"  class ='hid'  data-role="dialog" data-transition="none" data-enhance="false"><!-- dialog--> 
<?php if(!$defaultView) { ?>
<?php $this->load->view('V2/collegePredictorFilters1'); ?>
<?php } ?>
</div>

<div id= 'contentLoaderData' style='display: none;'>
    <?php  echo $contentLoaderData; ?>
</div>


<?php if($defaultView) { ?>
<div class='select-class'>
	<select name="selectCity" show-search="1" id="selectCity" class='hid'  append-selected-value="1">
		<optgroup>
			<?php if(strtoupper($examName) == 'MAHCET') {?>
    		<option value="-1">Select</option>
				<?php foreach($mainFilterCities as $key=>$value){ 
				if (!empty($value['id'])){?>
       		<option value="<?php echo $value['id'];?>" ><?php echo$value['cityName'];?></option>
				<?php }
			} } else{ ?>
    		<option value="">Select</option>
    			<?php foreach($states as $key=>$value){ ?>
	        	<option value="<?php echo $value['stateName'];?>" ><?php echo $value['stateName'];?></option>
			<?php 
			} } ?> 
		</optgroup>
	</select>
	<input type="hidden" id="selectCity_input"/>
</div>

<div class='select-class'>
	<select name="preferredCollege" show-search="1" id="preferredCollege" class='hid' data="" searchPlaceHolder= 'Search College' multiple="multiple">
	<optgroup>
		<?php foreach ($institutes as $index => $value):?>
			<option value="<?php echo $value['id']?>" ><?php echo $value['collegeName'].', '.$value['cityName'].', ' . $value['stateName'];?></option>
		<?php endforeach;?>	
	</optgroup>
	</select>
</div>
<div class='select-class'>
	<select name="userCategory" id="userCategory"  class='hid' >
	<optgroup>
		<?php foreach($categoryData as $key=>$value){ ?>
	    	<option value="<?php echo $key;?>" <?php if(!empty($data->categoryName) &&  $data->categoryName ==  $key) {echo 'selected';}?> ><?php echo $value;?></option>
		<?php } ?>
	</optgroup>
	</select>
</div>
<div class='select-class'>
	<select name="userCategory" id="selectStateCategory"  class='hid' >
	<optgroup>
	   <option value="HomeUniversity" selected>Home University</option>
	   <option value="OtherThanHome">Other Than Home University</option>
	   <option value="StateLevel">State Level</option>	
	</optgroup>
	</select>
</div>
<input type="hidden" id="preferredCollege_input" data=""/>
<input type="hidden" id="selectStateCategory_input" data=""/>
<input type="hidden" id="userCategory_input" value="<?=reset($categoryData);?>"/>

<?php } ?>
<script type="text/javascript">
var collegePredictorCourseCompare;
var GA_currentPage = 'collegePredictorMobile';
var totalResults = '<?=$totalResults;?>';
var numberOfRound = '<?php echo count($roundData); ?>';
var lazydBRecolayerCSS = '//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('tuple','nationalMobile'); ?>';
var groupId = '<?php echo $eResponseData['groupId'];?>';

<?php if($defaultView) { ?>
	var searchCall = 0;
<?php } else { ?>
	var searchCall = 1;
<?php } ?>
var examName = '<?=strtoupper($examName);?>';
var directoryName = '<?=$directoryName;?>';

//load addThis script on scroll
var addThisLoadCount = 1;
$(window).scroll(function() {
    var screenHeight = (typeof screen != 'undefined' && typeof screen.height != 'undefined' && screen.height>0) ? (screen.height)/2 : $('#page-header').outerHeight();
      if(addThisLoadCount == 1 && ($(window).scrollTop() > screenHeight)){
        var addthisScript = document.createElement('script');
        addthisScript.setAttribute('src', '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5c1b59506e2754b7');
        document.body.appendChild(addthisScript);
        addThisLoadCount = 2;
    }
});
</script>
<?php ob_end_flush(); ?>