<?php
	$this->load->view('listing/listingPage/listingHead',array('js' => array('ana_common','discussion','CAValidations'),'tab' => 'ana', 'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount));
	echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_ANA_PAGE',1);
?>

<script>var floatingRegistrationSource = 'LISTING_ANATAB_ASKQUESTION';</script>
<div id="page-contents">
	<div id="listing-left-col">
		<?php //echo Modules::run('CafeBuzz/ListingPageAnA/index', $institute,$categories); ?>
		<?php  echo Modules::run('CA/CADiscussions/getAllCoursesTuplesForInstitute',$institute->getId(),$currentLocation->getLocationId(),'',10); ?>

	</div>
	
	<div id="listing-right-col">
		<div id="rightWidget">
		</div>
		
		<div id="alumniWidget">
			
		</div>
		
		<div class="spacer20 clearFix"></div>
		<div id="shikshaAnalytics">
				
		</div>
	</div>
</div>
            <div class="clearFix"></div>
<?php
$this->load->view('listing/listingPage/listingFoot');
?>
<script>
	(function($j) {
		$j('#shikshaAnalytics').load('/listing/ListingPageWidgets/shikshaAnalytics/<?=$institute->getId()?>/0/<?=$pageType?>/'+ "?rand=" + (Math.random()*99999));
		$j('#alumniWidget').load('/listing/ListingPageWidgets/alumniSpeak/<?=$institute->getId()?>');
	})($j);  
</script>
