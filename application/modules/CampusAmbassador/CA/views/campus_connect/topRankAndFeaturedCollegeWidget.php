<!--start campus-college-container-->

<?php if(isset($result) && count($result) > 0) {?>
<div class="campus-college-container" id="topRankedCollegeContainer">
    <div class="campus-college-container-link">
        <h2 id="topRankedCollegeTab"><a href="javascript:void(0)" class="active" onclick="showTabWiseColleges('topRanked')">Top Ranked Colleges</a></h2>
        <h2 id="featuredCollegeTab"><a href="javascript:void(0)" onclick="showTabWiseColleges('featured')">Featured Colleges</a></h2>
        <div class="campus-college-sub-container" id="collegeWidget">            
                <?php $this->load->view('campus_connect/showRankedAndFeaturedColleges'); ?>
                
        </div>
    </div>
</div>
<?php } ?>
<!--end campus-college-container-->

