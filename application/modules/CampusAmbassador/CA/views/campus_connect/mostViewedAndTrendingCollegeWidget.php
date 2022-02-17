<!--start campus-college-container-->

<?php if(isset($instituteData) && count($instituteData) > 0) {?>
    <div class="campus-college-container" id="mostViewedCollegeContainer">
        <div class="campus-college-container-link">
            <h2 id="mostViewedCollegeTab"><a href="javascript:void(0)" class="active" onclick="showTabWiseCollegeSlide('mostViewed')">Most Viewed Colleges</a></h2>
            <h2 id="trendingCollegeTab"><a href="javascript:void(0)" onclick="showTabWiseCollegeSlide('trending')">Trending Colleges</a></h2>
            <div class="campus-college-sub-container" id="ColgWidget">            
                    <?php $this->load->view('campus_connect/showMostViewedAndTendingCollege'); ?>
                    
            </div>
        </div>
    </div>
<?php } ?>
<!--end campus-college-container-->

