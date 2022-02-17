<?php ob_start('compress'); ?>
<?php
$headerComponent = array(
                         'pageName'   => $boomr_pageid,
                         );

$this->load->view('mcommon5/header',$headerComponent);
?>
<div id="wrapper" style="background:#e5e5da;min-height: 413px;padding-top: 40px;" data-role="page" class="of-hide">
        <?php
                echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
                echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
        ?>
        <?php $this->load->view('RPHeader'); ?>
        <div data-role="content" style="background:#e6e6dc !important;">
        	<?php 
			      $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
			?>
                <div data-enhance="false">
               
	         <?php
		  
		    
		     $msg = json_decode($msg, true);
		     $minRange = $msg['startRank'];
		     $maxRange = $msg['closingRank'];
             $displayData['typeOfPredictor'] = 'rank';
             $this->load->view('CP/V2/breadcrumb',$displayData);
		     $this->load->view('rankPredictorResultPage', array('minRange'=>$minRange,'maxRange'=>$maxRange));
		     $this->load->view('mcommon5/footerLinks');
		     
		  ?>
                </div>
        </div>
	<div id="googleRemarketingDiv" style="display: none;"></div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>

<script>

var isAllowedDecimal = '<?php echo $rankConfigData[$examName]['inputField']['score']['isAllowedDecimal'];?>';
var examName = '<?php echo $examName;?>';
var examLabel = '<?php echo $rankConfigData[$examName]['inputField']['score']['label'];?>';
   
   $(document).ready(function(){
      
      window.scrollTo(0,0);
      

      <?php if($rankConfigData[$examName]['isTopCollegesWidget'] == 'YES'){?>
		<?php if($minRange > 0){
		  if ($examName == 'jee-main') {
    
			$examName = 'JEE-Mains';    
		  }else if($examName == ""){
		     
			$examName = 'JEE-Mains';   
		  }else{
			$examName = strtoupper($examName); 
		  }
		  ?>	
		jQuery.ajax({
		url: "/mRankPredictor/RankPredictorMController/getCollegePredictorResults/<?=$examName?>/<?=$minRange?>/<?=$downloadtrackingPageKeyId?>/<?=$shortlistTrackingPageKeyId?>/<?php echo $comparetrackingPageKeyId;?>/<?php echo $downloadtrackingPageKeyId;?>",
		success: function(result){
	       
		  if(result!=''){  
		      $('#collegePredictionResults').html(result);
		  }
	       }
	     });   
		<?php }else{ ?>
		
		  $('#collegeLists').hide();
		
		<?php } ?>
	<?php } ?>
		   
	   });

   function trackReqEbrochureClick(courseId){
       try{
              _gaq.push(['_trackEvent', 'HTML5_Rank_Predictor_Page_Request_Ebrochure', 'click',courseId]);
       }catch(e){}
   }
   var collegePredictorCourseCompare;
 </script>
<?php $this->load->view('mcommon5/footer'); ?>
<?php ob_end_flush(); ?>
<script type="text/javascript">
var examName = '<?php echo $examName;?>';
var groupId = '<?php echo $eResponseData['groupId'];?>';
$(window).load(function () {
    collegePredictorCourseCompare = new collegePredictorCourseCompareClass();
    collegePredictorCourseCompare.refreshCompareCollegediv();
    myCompareObj.setRemoveAllCallBack('collegePredictorCourseCompare.removeItem');
});
</script>