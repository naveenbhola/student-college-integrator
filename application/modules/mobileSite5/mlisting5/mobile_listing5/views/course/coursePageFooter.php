<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer');?>
<input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP" value="<?=$courseObj->getId()?>">
<input type="hidden" id="instituteIdQP" name="instituteIdQP" value="<?=$courseObj->getInstituteId()?>">
<input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_Listing_MOB">
<input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('googleChartsLoader','nationalMobile'); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('googleCharts-1.0.2','nationalMobile'); ?>"></script>

<script>
	var courseId = '<?=$courseObj->getId()?>',GA_userLevel='<?=$GA_userLevel?>',GA_currentPage='<?=$GA_currentPage?>',instituteId = '<?=$courseObj->getInstituteId()?>';
	initializeNationalCoursePage(courseId);
	<?php if($_REQUEST['showAllBranches'] == 1 && $isMultilocation){?>
          if($("#location-layer").length > 0)
              showLocationLayer();
    <?php } ?>
	var myCompareObj = new myCompareClass();
	var isCompareEnable = true;
	var ga_page_name = '<?=$GA_currentPage;?>';
	var ga_user_level = '<?=$GA_userLevel;?>';
	lazyLoadCss("//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('tuple','nationalMobile');?>");
	var contactKeyId            = '<?=$ampKeys['CONTACT_WIDGET']?>';
	var internKeyId             = '<?=$ampKeys['INTERNSHIP_BROCHURE'];?>';
	var placementKeyId          = '<?=$ampKeys['PLACEMENT_BROCHURE'];?>';
	var applyNowKeyId           = '<?=$ampKeys['APPLY_NOW_WIDGET']?>';
	var applyNowTopKeyId        = '<?=$ampKeys['APPLY_NOW_TOP_WIDGET']?>';
	var compareStickyKeyId      = '<?=$ampKeys['COMPARE_STICKY_WIDGET']?>';
	var brochureStickyKeyId     = '<?=$ampKeys['BROCHURE_STICKY_WIDGET']?>';
	var shortlistTopwidgetKeyId = '<?=$ampKeys['SHORTLIST_TOP_WIDGET']?>';
	
	var pos             = '<?=$pos;?>';
	var uriActionType   = '<?=$actionType;?>';
	var uriFromWhere    = '<?=$fromwhere;?>';
	var replaceStateUrl = '<?=$replaceStateUrl;?>';
	var instituteName   = '<?=base64_encode($courseObj->getInstituteName());?>';
	var isInternalFlag  = '<?=$courseDates['internalFlag'];?>';
	var applyLink       = '<?php echo $courseDates['type'] == 'onlineForm' ? true :false;?>';
	var brochureDownloaded = '<?=$brochureDownloaded;?>';
	contentMapping = JSON.parse('<?php echo json_encode(Modules::run('common/WebsiteTour/getContentMapping','cta','mobile')); ?>');
	$(window).load(function(){
		if(uriActionType == 'compare' && $('#popupBasic-Short-Compare').css('display') != 'none')
		{
			setTimeout(function(){$(window).scrollTop($('#popupBasic-Short-Compare').offset().top - 50);},1000);		
		}
	});
</script>