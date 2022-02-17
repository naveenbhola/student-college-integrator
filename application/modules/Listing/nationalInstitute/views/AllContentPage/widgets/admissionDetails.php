<?php 
$GA_Tap_On_View_More = 'VIEW_MORE_ADMISSION';
if($instituteObj->getAdmissionDetails() != ''){ ?>
        <div class="data-card" style="margin-bottom:20px;">
                <h2 class="cmn-h2 mb20">Admission Process: Overview <?php if($coursesData['mostPopularCourse']){ ?><span class="abt-pipe"></span> <a id="course_sec_scroll" href="javascript:void(0);">Course Specific</a> <?php } ?>
                </h2>
                <div class="admsn-dsc gradient blog-cont" id="admissionDetails">
			<p class="cmn-fnt">
				<?=$instituteObj->getAdmissionDetails()?>
			</p>
                </div>
                <div class="gradient-col" style="display:none;" id="admissionViewMore">
                        <a href="javascript:void(0)" class="btn-tertiary" onClick="showAdmissionDetails();" ga-attr="<?=$$GA_Tap_On_View_More;?>">View More</a>
                </div>
        </div>
<?php } ?>
