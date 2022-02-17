<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'noIndexNoFollow' => true,
      'noHeader' => true
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide">


    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       <div id="page-header-container" style=""><?php //echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP');?></div>
    </header>
</div>

<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php $this->load->view('/mcommon5/footer',array("jsMobileFooter" => array('mCourseAmp')));?>    
<script type="text/javascript">
    var pageName = 'responseAmpPage';
    var isOpenResponeForm = true;
    var isAmpResponseForm = true;
  jQuery(document).ready(function($) {

     $('#wrapper').css({'padding-top':'0px'});
	 if(isOpenResponeForm)
        {
          var responseActionType = '<?=$actionType;?>';
          var fromwhere = '<?=$fromwhere;?>';
          if(fromwhere == 'exampage'){
            var listingId = '<?=$examGroupId;?>';
            var listingType = 'exam';
            var formdata = {
                'trackingKeyId' : '<?=$trackingKeyId;?>',
                'redirect' : '<?=$redirectUrl;?>',
                'pos': '<?=$position?>',
                'fileNo': '<?=$fileNo?>',
                'instituteName': '<?=$instituteName?>',
                'course': '<?=$examGroupId?>',
                'courseId': '<?=$courseId?>',
                'isInternal': '<?=$isInternal?>',
                'clickId': '<?=$clickId?>',
                'sectionName': '<?=$sectionName?>'
            };
          }else{
            isOpenResponeForm = false;
            var listingId = '<?=$listingId;?>';
            var listingType = '<?=$listingType;?>';          
            var formdata = {
              'shortlistKeyId': '<?=$ampKeys['SHORTLIST_TOP_WIDGET'];?>',
              'brochureKeyId': '<?=$ampKeys['BROCHURE_STICKY_WIDGET'];?>',
              'compareKeyId' : '<?=$ampKeys['COMPARE_STICKY_WIDGET'];?>',
              'shortlistStickyId': '<?=$ampKeys['SHORTLIST_STICKY_WIDGET'];?>',
              'applyNowTopKeyId' : '<?=$ampKeys['APPLY_NOW_TOP_WIDGET'];?>',
              'applyNowWidgetKeyId' : '<?=$ampKeys['APPLY_NOW_WIDGET'];?>',
              'contactKeyId' : '<?=$ampKeys['CONTACT_WIDGET'];?>',
              'internKeyId' : '<?=$ampKeys['INTERNSHIP_BROCHURE'];?>',
              'placementKeyId' : '<?=$ampKeys['PLACEMENT_BROCHURE'];?>',
              'getFeeDetailsKeyId': '<?=$ampKeys['GET_FEE_DETAILS'];?>',
              'redirect' : '<?=$redirectUrl;?>',
              'pos': '<?=$position?>',
              'fromFeeDetails': '<?=$fromFeeDetails?>',
        	    'interestedInKeyId': '<?=$ampKeys['BROCHURE_INTERESTED_IN_WIDGET'];?>',
        	    'similarKeyId': '<?=$ampKeys['SIMILAR_COLLEGES'];?>',
        	    'alsoViewedKeyId': '<?=$ampKeys['ALSOVIEWED_COLLEGES'];?>',
              'rhsInstituteKeyId': '<?=$ampKeys['RHS_INSTITUTE_WIDGET'];?>'
            };
          }
        openResponseForm(listingId,listingType,responseActionType,fromwhere,formdata);
    }
        //responseForm.showResponseForm(listingId, responseActionType, 'listingType', formdata); 

    $(window).on("navigate", function (event, data) {
      var direction = data.state.direction;
      setTimeout(function(){
          if(direction == 'back' && !$('.sltNone').is(":visible")){
              $.mobile.back();
          }
      }, 500);
    });
  });

</script>
<?php ob_end_flush(); ?>
