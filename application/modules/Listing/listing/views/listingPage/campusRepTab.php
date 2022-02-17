<?php
	$this->load->view('listing/listingPage/listingHead',array('js' => array('caCoursePage','ana_common','discussion','processForm','CAValidations','user'),'tab' => 'campusRep', 'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount));
	echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_ANA_PAGE',1);
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$j = $.noConflict();
</script>
<script>var floatingRegistrationSource = 'LISTING_ANATAB_ASKQUESTION';</script>
<div id="page-contents">
	<div id="listing-left-col">
		<?php //echo Modules::run('CafeBuzz/ListingPageAnA/index', $institute,$categories); ?>
		<?php echo Modules::run('CA/CADiscussions/getCourseTuple',$course->getId(),$institute->getId(),'campusConnect'); ?>
		<?php echo Modules::run('CA/CADiscussions/getCourseQnA',$page_no,$course_id,'campusConnect'); ?>
		<script>
		var courseClientId = '<?=$course->getClientId();?>';
		loadBadges();
         </script>        
		
				<?php echo Modules::run('CA/CADiscussions/getQuestionForm',$course->getId(),$institute->getId()); ?>
		<?php echo Modules::run('CA/CADiscussions/getCourseLinks',$course_id,false); ?>
	</div>
	
	<div id="listing-right-col">
		<div id="rightWidget">
		</div>
		
		<div id="alumniWidget">
			
		</div>
		
		<div class="spacer20 clearFix"></div>
		<div id="shikshaAnalytics">
				
		</div>

                <?php
                if(!empty($rankingWidgetHTML)){
                ?>
                 <div class="spacer20 clearFix"></div>
                <div id="rankingPageWidget" style="padding-bottom:10px;">
                        <?php echo $rankingWidgetHTML; ?>
                </div>
                <?php
                }
                ?>

                <div class="section-cont">
                <?php
                                $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                                $this->load->view('common/banner',$bannerProperties);
                ?>
                </div>

                <div class="section-cont-title">Like us on Facebook</div>
                        <div class="shadow-box">
                        <div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="265" data-show-faces="true" data-border-color="#f2f2f2" data-stream="false" data-header="false"></div>
                        </div>
                </div>

	</div>
</div>
            <div class="clearFix"></div>
<?php
$this->load->view('listing/listingPage/listingFoot');
?>
<script>
	(function($j) {
		$j('#shikshaAnalytics').load('/listing/ListingPageWidgets/shikshaAnalytics/<?=$institute->getId()?>/0/institute/'+ "?rand=" + (Math.random()*99999));
		$j('#alumniWidget').load('/listing/ListingPageWidgets/alumniSpeak/<?=$institute->getId()?>');
	})($j);  
</script>

<script>
var hash = location.hash.replace("#","");
if(hash == 'ask-question'){
    $j('#ask_question_askAQuestion').focus();
    $('askQuestionFormDiv').scrollIntoView(false);
}
</script>
