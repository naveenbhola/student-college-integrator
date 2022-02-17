<?php 
if(!empty($courseDetails)) {

	$this->load->helper(array('listingCommon/listingcommon'));

	foreach($courseDetails as $courseObj) {
		
		$reviewData = Modules::run('CollegeReviewForm/CollegeReviewController/getAverageRatingAndCountByCourseId', $courseObj->getId());	

?>
<div class="collegeDescription2">
<?php
        if($reviewData['averageRating']){
            $alumniRating = round($reviewData['averageRating']);?>						
            <div class="alumniRating">
	            <span>Alumni Rating:&nbsp;&nbsp;</span>
	            <span>
		            <?php for($i=0;$i<$alumniRating;$i++):?>
		            	<img border="0" src="/public/images/nlt_str_full.gif">
		            <?php endfor;?>
	            </span>
	            <span class="rateNum">&nbsp;<?php echo $alumniRating;?>/5</span>
            </div>
        <?php } ?>
 

		<div id="OF_INST_COURSE_DETAILS">
			
			<?php if($pageType == 'departmentpage') { ?>
            	<h5><a href="<?php echo $courseObj->getURL(); ?>"><?php echo $courseObj->getName();?></a></h5>
			<?php } else { ?>
				<h2 style="font-size:14px; line-height:18px; font-weight:bold; padding-bottom:3px; display:block">
					<a href="<?php echo $courseObj->getURL(); ?>"><?php echo $courseObj->getName();?></a>
				</h2>
			<?php } ?>

	         <p>
				<?php 
				$instInfo = getExtraInfo($courseObj);
				echo $instInfo['extraInfo']['duration']?$instInfo['extraInfo']['duration']:"";
				if($instInfo['extraInfo']['duration'] && $instInfo['extraInfo']['educationType']) { echo ', '; }
				echo $instInfo['extraInfo']['educationType']?$instInfo['extraInfo']['educationType']:"";
				if ($instInfo['courseLevel'] || $instInfo['courseCredential']){
				    echo ", ".$instInfo['courseLevel'];
				    if($instInfo['courseCredential']){
				    	echo ' '.$instInfo['courseCredential'];
				    }
				}
				?>
	        </p>

			<div class="Fnt11 mb6">
				<?php
				$approvalsArray = array();
				$approvals = $courseObj->getRecognition();
                foreach($approvals as $approval) {
			        if($approval->getName() != 'NBA'){
                       $approvalsArray[] = $approval->getName().' Approved'; 
                    }
				}
				echo implode(', ',$approvalsArray);
				?>
			</div>

 			<?php if($pageType == 'departmentpage') { ?>
			<div style="margin-top:3px;">
	            <?php
	            $this->load->library('nationalCourse/CourseDetailLib');
	            $CourseDetailLib = new CourseDetailLib;
	            $courseStatusArray = $CourseDetailLib->getCourseStatus(array($courseObj->getId()));
	            foreach ($courseStatusArray as $key => $value) {
	                $courseStatus = $value['courseStatusDisplay'];
	            }
	            echo implode(", ", $courseStatus);
	            ?>
            </div>
        	<?php } ?>

            <div class="feeStructure2">
                <?php 


                if($pageType == 'externalclientpage') { 
                    $exams = $courseObj->getEligibilityMappedExams();  

                    ?>

                        <ul>                                                                        
                            <?php if (count($exams) > 0) { ?>
                            <li>
                                <label>Eligibility : </label> 
                                <span>
                                <?php
                                echo implode(',',$exams);
                                    ?>	
                                </span>
                            </li>
                            <?php } ?>

                            <?php
                            if ($courseObj->getId() == 12873) {
                                echo "<li><label>Selection criteria : </label><span>MICAT and GE & PI</span></li>";
                            }
                            ?>
                        </ul>

                <?php
                } else {

	                if($instInfo['fees']){
	                    echo $instInfo['fees'];

		                $feeObj = $courseObj->getFees();
		                if(is_object($feeObj)){
		                	$feeDisc = $feeObj->getFeeDisclaimer();
		                }
		                if($feeDisc){ 
		                    echo FEES_DISCLAIMER_TEXT; 
		                }
					}
					
               	}
                ?>
            </div>
		</div>
	</div>
<?php
	}
}
?>
