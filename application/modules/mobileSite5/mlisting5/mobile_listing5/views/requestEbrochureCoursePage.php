 <?php  if(checkEBrochureFunctionality($courseComplete)){ ?>
            <p>
                <?php if(in_array($course->getId(),$appliedCourseArr)){?>
                        <a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>_another"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
                <?php }else{ ?>
			 <a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>_another" onClick="trackRequestEbrochure('<?=$course->getId();?>');
makeResponse('<?=$topdTrackingPageKeyId?>');"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
                <?php } ?>
		
		
		        <!--Add-to-compare--->
	 
			<?php
			$data['instituteId'] = $institute->getId();
			$data['courseId']    = $course->getId();
			$data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
			$this->load->view('/mcommon5/mobileAddCompare',$data);
			?>
			
			<!--end-->
            </p>
	   <?php if(in_array($course->getId(),$appliedCourseArr)){
		      echo "<script>var rebButtonStatus = 'SUBMIT';</script>";
	   }else{
		      echo "<script>var rebButtonStatus = 'NOTSUBMIT';</script>";
	   }
	   echo "<script>var courseId = ".$course->getId().";</script>";?>
    <?php } ?>
  <script>
	   
	   function trackRequestEbrochure(courseId){
	       try{
	       var listing_id  = courseId;
	       _gaq.push(['_trackEvent', 'HTML5_CourseListing_Page_Request_Ebrochure', 'click', listing_id]);
	       }catch(e){}
	   }
</script>
