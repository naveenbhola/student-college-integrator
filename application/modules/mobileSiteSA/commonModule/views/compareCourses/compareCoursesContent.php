<section>
<div class="compare-section">
	<div class="compare-detail-sec">
        <?php $this->load->view('compareCourses/widgets/compareCoursesRecommendedCourses');?>
        <?php  $this->load->view('compareCourses/widgets/compareCoursesAboutCourses');?>
        <?php  $this->load->view('compareCourses/widgets/compareCoursesFeesDetails');?>
        <?php  $this->load->view('compareCourses/widgets/compareCoursesEntryRequirements');?>
        <?php  $this->load->view('compareCourses/widgets/compareCoursesApplicationProcess');?>        
        <?php  $this->load->view('compareCourses/widgets/compareCoursesUniversityInfo');?>        
        <?php  $this->load->view('compareCourses/widgets/compareCoursesMiscellaneous');?>
    </div>
    <input type="hidden" id="courseid" name="courseid">
    <input type="hidden" id="source" name="source">
    <input type="hidden" id="ref" name="ref">
</div>
 <div class="compare-detail-sticky-layer" style="display:none;" id="compHeaderSticky">
<table>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { 
            $univId            = $courseObj->getUniversityId();
            $universityObject  = $univDataObjs[$univId];?>
        <td><div class="compare-detail-content univlabel"><?php echo htmlentities(formatArticleTitle($courseObj->getUniversityName(),25));?></div>
		<div class="compare-detail-content courselabel"><?php echo  htmlentities(formatArticleTitle($courseObj->getName(),25)); ?></div></td>
        <?php } ?>
		<?php if($recommendedCourses && count($recommendedCourses)>0){ ?>
		<td>
            <div class="compare-detail-content">
                <div class="similar-compare-sec">
					<a class="fnd-btn" id="compareSearchContainerLink" href="#compareSearchContainer" data-rel="dialog" data-transition="slide"><i class="sprite src-headIcn"></i>Find a college</a>
                    <h2 class="similar-compare-title">Or compare with similar</h2>
                    <ul class="similar-list">
                        <?php $this->load->view('commonModule/compareCourses/widgets/recommendedCourseListForCompare'); ?>
                    </ul>
                 </div>
            </div>
        </td>
		<?php } ?>
        <?php if($coursesCount == 1 && (!$recommendedCourses || count($recommendedCourses)==0)){?>
			<th id="lastCellDiv" style="vertical-align:middle;font-size:11px;color:#999;">
				<p>[ + ] Add another course</p>
			</th>
		<?php } ?>
    </tr>
    
</table>
    </div>
</section>    
<div class="clearFix">&nbsp;</div>