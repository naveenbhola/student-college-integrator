<?php if(count($mentorListData)>0){ ?>
<section class="clearfix content-section">
                <div class="mentor-widget-box get-mentor-sec clearfix">
                <h2 class="mentor-widget-title">Our Student Mentors</h2>
                <p style="margin-top:15px; font-size:11px;" class="flRt">Showing <?= ($pageNo*4)+1?> - <?= (($pageNo+1)*4) > $mentorCount ? $mentorCount : (($pageNo+1)*4) ?> of <?=$mentorCount?> Mentors</p>
                <div class="stu-mentor-list">
                        <ul>
	                        <?php $this->load->view('mentorship/studentMentorTupleList'); ?>
				<div id="otherMentors" style="display:none;"></div>
				<div id="loaderDiv" style="display:none;text-align:center;"><img src="/public/mobile5/images/ajax-loader.gif" border=0></img></div>
                        </ul>
                        <div class="clearfix"></div>
                        <a id="viewMoreMentorLink" class="student-more-btn" href="javascript:void(0);" onClick="getMentorList();">View More</a>
                </div>
                </div>
</section>

<script>
var totalPagesAvailable = Math.ceil(<?=$mentorCount?>/4);
var pageNumber = 0;
</script>
<?php } ?>


