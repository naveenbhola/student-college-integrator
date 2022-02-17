<!-- START : INSTITUTE NAVIGATION SECTION-->
<div class="institute-nav clearwidth" style="position: relative;">
    <ul class="institute-tab flLt">
        <!--<li class="active" id = "categoryPageListingsSecNav" ><a href="javascript:void(0);" uniqueattr="ABROAD_CAT_PAGE/NavBar_coursesFoundTab" onclick = "slowScrollToTop('scrollToThisDiv', 5); enableTab ('categoryPageListingsSec')"><i class="cate-sprite inst-icon"></i><span id="foundCoursesCount"><?//=$noOfCourses?></span> courses found</a> <i class="cate-sprite pointer"></i></li>-->
		<?php if($noOfUniversities>1){$collegeText = " results found";}else{$collegeText = " result found";} ?>
		<li class="active" id = "categoryPageListingsSecNav" ><a href="javascript:void(0);" onclick = "studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'NavBar', 'coursesFoundTab'); slowScrollToTop('scrollToThisDiv', 5); enableTab ('categoryPageListingsSec')"><i class="cate-sprite inst-icon"></i><span id="foundCoursesCount"><?=$noOfUniversities.$collegeText?></span></a> <i class="cate-sprite pointer"></i></li>
        <li id = "userShortListedListingsSecNav" ><a href="javascript:void(0);" id ="shortListTab" onclick = "studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'NavBar', 'shortlistedCoursesTab'); slowScrollToTop('scrollToThisDiv', 5); enableTab ('userShortListedListingsSec','<?=(str_replace("in All Countries","Abroad",$catPageTitle))?>')"><i class="cate-sprite shrtlist-icon"></i>Saved courses (<?=$userShortlistedCourseIds['count']?>)</a> <i class="cate-sprite pointer"></i></li>
    </ul>

	<?php if(! $isZeroResultPage || $categoryPageRequest->isExamCategoryPage()) {
		$this->load->view('categoryList/abroad/widget/categoryPageSortBy'); ?>
	 
		<div class="sort-by-help" id="sortByHelp" style="display:<?=($showGutterHelpText)?"":"none"; ?>">
			<i class="cate-sprite help-arrow-2"></i>
			<p>Use this dropdown to sort by Popularity,<br/>Fees or Eligibility exams</p>     
		</div>
	<?php } ?>
</div>

<!-- END : INSTITUTE NAVIGATION SECTION-->