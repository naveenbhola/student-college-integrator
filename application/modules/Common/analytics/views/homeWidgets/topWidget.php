<div class="container-fluid">
    <div class="row header_bg">
        <div class="col-md-12">
            <div class="hdr-dv">
                Discover the most visited colleges, universities, courses, <br/>specializations & exams and their trends on Shiksha
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-blk clearfix col-blk2">
                <?php if(!(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))){
                ?>
                <div class="col-xs-8 col-sm-8 col-md-10">
                    <div class="row">
                        <input type="text" id="srch-field-search" class="srch-field" placeholder="Enter a College, Course, Specialization or Exam" autocomplete="off">
                            <div id="search-analytics-layer" class="search-college-layer" style="display: none;"></div>                        
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-2">
                    <div class="row">
                        <button class="btn btn-default clr-or exp-btn">Explore</button>
                    </div>
                </div>
                <?php  } else { ?>
                <div class="col-xs-10 col-sm-10 col-md-10">
                    <div class="row search-field">
                        <input type="text" id="srch-field-search" class="srch-field" placeholder="Enter a College, Course, Specialization or Exam" autocomplete="off">
                            <ul id="search-analytics-layer" class="college-course-list" style="display: none;"></ul>
                    </div>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div class="row">
                        <button class="btn btn-default clr-or exp-btn"><i class=" msprite search-icn"></i></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-blk clearfix agg_data">
                <div class="col-xs-6 col-sm-3"><strong><?php echo $overall_metrics['totalListings'];?>+</strong>College & Universities</div>
                <div class="col-xs-6 col-sm-3"><strong><?php echo $overall_metrics['examCount'];?>+</strong>Exams</div>
                <div class="col-xs-6 col-sm-3"><strong><?php echo $overall_metrics['baseCourseCount'];?>+</strong>Degrees & Diplomas</div>
                <div class="col-xs-6 col-sm-3"><strong><?php echo $overall_metrics['specializationCount'];?>+</strong>Specializations</div>
            </div>
        </div>
    </div>

</div>