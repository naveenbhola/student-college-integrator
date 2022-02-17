<nav class="tabs">
    <ul>
        <li class="rmc-tab active" id="rmc-tab"><a href="javascript:void(0);" onclick="switchRMCorShortlist('rmc','<?php echo $rmcCourses;?>');">Rate my chance (<span id="rmcCoursesCounter"><?php echo $rmcCourses;?></span>) </a></li>
        <li id="shortlist-tab" class="shortlist-tab"><a href="javascript:void(0);" onclick="switchRMCorShortlist('shortlist','<?=count($userShortlistedCourses)?>');">Saved (<span id="shortListPageTitleCounter"><?=count($userShortlistedCourses)?></span>) </a></li>
    </ul>
</nav>        
<div id="rmc-tuples" class = "rmc-tuples">
    <div id="rmcTuplesSection">
    <?php $this->load->view("shortlistPage/rateMyChanceListings"); ?>
    </div>
</div>
<div id="shortlist-tuples" class = "shortlist-tuples">
	<div id="shortListTupleSection">
    	<?php $this->load->view("shortlistPage/shortlistListings"); ?>
	</div>
</div>
<script>
var shortlistTab = '<?=$showShortlistTab?>';
</script>