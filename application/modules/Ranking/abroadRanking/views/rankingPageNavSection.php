<!-- START : INSTITUTE NAVIGATION SECTION-->
<?php
	if($rankingPageObject->getType() == 'course'){
		$typestring = 'Colleges';
	}
	else{
		$typestring = 'Universities';
	}
?>
<div class="institute-nav clearwidth" style="position: relative;">
    <ul class="institute-tab flLt">
		<li class="active" id = "categoryPageListingsSecNav" >
			<a href="javascript:void(0);" onclick = "studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'NavBar', 'coursesFoundTab'); slowScrollToTop('scrollToThisDiv', 5); enableTab ('categoryPageListingsSec')"><i class="cate-sprite inst-icon"></i>
				<span id="foundCoursesCount">
					<?= $noOfCourses;?> <?=$typestring?> Found
				</span>
			</a>
			<i class="cate-sprite pointer"></i>
		</li>
        <li id = "userShortListedListingsSecNav" ><i class="cate-sprite pointer"></i></li>
    </ul>

	<?php if($rankingPageObject->getType() == 'course'){
		//$this->load->view('rankingPageSortBy'); ?>
	 
		<div class="sort-by-help" id="sortByHelp" style="display:<?=($showGutterHelpText)?"":"none"; ?>">
			<i class="cate-sprite help-arrow-2"></i>
			<p>Use this dropdown to sort by Popularity,<br/>Fees or Eligibility exams</p>     
		</div>
	<?php } ?>
</div>

<!-- END : INSTITUTE NAVIGATION SECTION-->