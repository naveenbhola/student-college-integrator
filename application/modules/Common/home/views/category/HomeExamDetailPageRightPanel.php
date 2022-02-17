<?php $this->load->view('home/category/HomeExamPageFeaturedColleges'); ?>
    <div class="lineSpace_10">&nbsp;</div>
<?php $this->load->view('home/category/HomeExamDetailPageGroupsPanel'); ?>
    <div class="lineSpace_10">&nbsp;</div>
<?php $this->load->view('home/category/HomeExamDetailPageEventsPanel'); ?>
	<div class="lineSpace_10">&nbsp;</div>
<?php $this->load->view('home/category/HomeExamDetailPageRelatedArticlesPanel'); ?>
	<div class="lineSpace_10">&nbsp;</div>
	<!--Internal_style_for_change_mockup-->
<style>
.raised_greenGradient {background: transparent; } 
.raised_greenGradient .b1, .raised_greenGradient .b2, .raised_greenGradient .b3, .raised_greenGradient .b4, .raised_greenGradient .b1b, .raised_greenGradient .b2b, .raised_greenGradient .b3b, .raised_greenGradient .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_greenGradient .b1, .raised_greenGradient .b2, .raised_greenGradient .b3, .raised_greenGradient .b1b, .raised_greenGradient .b2b, .raised_greenGradient .b3b {height:1px;} 
.raised_greenGradient .b2 {background:#D6DBDF; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;} 
.raised_greenGradient .b3 {background:#ffffff; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;} 
.raised_greenGradient .b4 {background:#ffffff; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;} 
.raised_greenGradient .b4b {background:#ffffff; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;} 
.raised_greenGradient .b3b {background:#ffffff; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;} 
.raised_greenGradient .b2b {background:#ffffff; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;} 
.raised_greenGradient .b1b {margin:0 5px; background:#D6DBDF;} 
.raised_greenGradient .b1 {margin:0 5px; background:#ffffff;} 
.raised_greenGradient .b2, .raised_greenGradient .b2b {margin:0 3px; border-width:0 2px;} 
.raised_greenGradient .b3, .raised_greenGradient .b3b {margin:0 2px;} 
.raised_greenGradient .b4, .raised_greenGradient .b4b {height:2px; margin:0 1px;} 
.raised_greenGradient .boxcontent_greenGradient {display:block; background:#FFFFFF; border-left:1px solid #D6DBDF; border-right:1px solid #D6DBDF;}
.testPreOrangeColor{color:#FD8103}
</style>
	<!--Internal_style_for_change_mockup-->
<?php $this->load->view('common/inviteFriendsWidget'); ?>
     <?php
        $metricsData = array('listing_type'=>'Article','viewCount'=>$blogView);
    ?>
<?php $this->load->view('common/viewMetrics', $metricsData); ?>
    <div class="lineSpace_10">&nbsp;</div>
    <div class="lineSpace_10">&nbsp;</div>
