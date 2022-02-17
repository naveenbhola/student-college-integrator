<?php 
$GA_currentPage = "ADMISSION PAGE";
$GA_Tap_On_Filter = "FILTER";
$GA_Tap_On_Filter_Stream = 'STREAM_FILTER';
$GA_Tap_On_Filter_Course = 'COURSE_FILTER';
$GA_Tap_On_Category = "CATEGORY_FILTER";
?>
<div class="data-card left-main-sec">
		<h2 class="cmn-h2 mb10">Admission Process for <?php echo htmlentities($instituteName);?> courses</h2>
		<p class="cmn-fnt">To find out information on eligibility, process and important dates, select a course</p>
		<div class="admsn-dsc drop-article">
			<div class="dat gen-cat">
			 <label>Stream</label>
			 <div class="dropdown-primary" id="categoryEligibilityOptions" ga-attr="<?=$GA_Tap_On_Filter;?>">
                    <span class="option-slctd"><?php echo htmlentities($coursesData['streams'][$coursesData['mostPopularStream']]->getName());?></span>
                    <span class="icon"></span>
                    <ul class="dropdown-nav" style="display: none;">
                    <div id="streamScrollDiv">
                    <div class="scrollbar disable" ><div class="track">
						<div class="thumb"><div class="end"></div></div></div>
					</div>
						<div class="viewport">
						<div class="overview list">
	                        <?php 
								foreach ($coursesData['sortedStreams'] as $key => $value) {
							?>
								<li class="li-dropdown" onclick='$j("#selectedCourseId").val("");$j("#stream").val("<?php echo $value->getId();?>");fireGTM("stream","<?php echo $value->getId();?>");updateAllContentByAjax();'><a class="li-dropdown-a" value="<?php echo $value->getId();?>" ga-attr="<?=$GA_Tap_On_Filter_Stream;?>"><?php echo htmlentities($value->getName());?></a></li>
							<?php } ?>
						</div>
						</div>
					</div>
                    </ul>
            </div>
			</div>
			<div class="dat">
			 <label>Course</label>
				 <div class="m-drop">
					<p class="cmn-fnt multiselectdrop" id="finac" ga-attr="<?=$GA_Tap_On_Filter;?>"><?php echo htmlentities($coursesData['courseObjects'][$coursesData['mostPopularCourse']]->getName());?></p>
					 <div class="m-cnt drop-list" style="height:auto;">
			  <div class="srch-data">
				 <div class="srch-box">
				   <i class="src-icn"></i>
					<input type="text" placeholder="Search Course" id="searchCourseTxt">
					<i class="rmv-icn hide">×</i>
				 </div>
			  </div>
		  <div class="data-show scrollbar1" id="courseScrollList">  
			  <div class="scrollbar">
				<div class="track">
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
			<div class="overview">
				<ul class="ul-data">
				<?php
					foreach ($coursesData['courseCollegeGrouping'] as $collegeName => $coursesList) {

						$groupCollegeName = "";
						if($collegeName != 'other'){
							$instName = explode('__',$collegeName);
					  		$groupCollegeName = $instName[0];
					  	}else if($collegeName == 'other' && count($coursesData['courseCollegeGrouping']) != 1){
					  		$groupCollegeName = "Other Academic Units";
					  	}
				?>
						<li class="p-c-n">

						  <?php 
						  if($groupCollegeName){
						  	 echo '<p class="cmn-fnt">'.$groupCollegeName.'</p>';
						  	} ?>
							<ul class="inner-ul">
							<?php foreach ($coursesList as $courseObj){ ?>
								<li class="c-n" onclick='$j("#selectedCourseId").val("<?php echo $courseObj->getId();?>");fireGTM("course","<?php echo $courseObj->getId();?>");updateAllContentByAjax();'><a href="javascript:void(0);" class="cmn-fnt" ga-attr="<?=$GA_Tap_On_Filter_Course;?>"><?php echo htmlentities($courseObj->getName());?></a></li>
							<?php } ?>
							</ul>
						</li>	
				<?php
					}
				?>
				</ul>
				<div class="no-data-div hid">No Result !!!</div>
			</div>
			</div>
		</div>
					</div>
				</div>
			</div>
		</div>
		<!--table column-->
<?php $this->load->view("nationalCourse/CoursePage/CourseEligibilityWidget",array('pageName' => 'Admission'));?>

<?php
if(!empty($admissions) || !empty($importantDatesData['importantDates']))
	$this->load->view('nationalCourse/CoursePage/CourseAdmissionWidget', array("noHeading" => 1,'pageName' => 'Admission','strlimit' => 320));
if (!empty($admissions)){
    $this->load->view('nationalCourse/CoursePage/CourseAdmissionLayer');
}
if(!empty($showImportantViewMore)){
    $this->load->view('nationalCourse/CoursePage/CourseImportantDatesLayer');
}
if(!empty($predictorData) && count($predictorData) > 1)
        $this->load->view('nationalCourse/CoursePage/CoursePredictorLayer',array('pageName' => 'Admission'));
    ?>

<?php if(!empty($predictorData)) {
    if($pageName = 'Admission')
    {
        $gaAttr = "ga-attr = 'FIND_OUT_PREDICTOR'";
    }
    ?>
<div class="findOut-sec">
    <?php if(count($predictorData) > 1 ) { ?>
    <h2 class="para-3">Want to find out your chances of getting into this college ? <a href="javascript:void(0)" class="btn-secondary mL10 eli-tool" <?=$gaAttr;?>>Find-out Now</a></h2>
    <?php } else { ?>
    <h2 class="para-3">Want to find out your chances of getting into this college ? <a href="javascript:void(0)" data-url="<?php echo $predictorData[0]['url']?>" data-name="<?php echo $predictorData[0]['name']?>" class="btn-secondary mL10 eli-tool" <?=$gaAttr;?>>Find-out Now</a></h2>
    <?php } ?>
</div>
<?php } ?>
</div>
<script type="text/javascript">
	var baseCourseMapping = JSON.parse('<?php echo $coursesData["baseCourseMapping"];?>');
</script>