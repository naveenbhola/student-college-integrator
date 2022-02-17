<?php //check the $abuseFlag status and accordingly show ONE of 'report abuse' or 'relive' button ?>

<!-- <div class="h33 bgcolor_gray"> -->
   <div>
      <div class="row">
         <?php if($details['status'] == 'live'){ ?>
         <div class="buttr2">
             <button class="btn-submit5 w20" name="publishListing" id="publishListing" value="publishListing" type="button" onClick="window.open('<?php echo site_url(); ?>getListingDetail/<?php echo $type_id; ?>/<?php echo $listing_type; ?>','hurray','fullscreen=yes,menubar=yes,status=yes,location=yes,toolbar=yes,scrollbars=yes,directories=yes'); return false;">
                 <div class="btn-submit5"><p class="btn-submit6">See Detail Page</p></div>
            </button>
         </div>
         <?php if($validateuser[0]['usergroup'] == 'cms') :?>
         <div class="buttr2">
            <button class="btn-submit5 w20" name="deleteListing" id="deleteListing" value="deleteListing" type="button"  onClick="deleteListing('<?php echo $listing_type; ?>','<?php echo $type_id; ?>');">
               <div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
            </button>
         </div>
         <?php endif;?>
         <?php if($validateuser[0]['usergroup'] == 'cms') { ?>
         <div class="buttr2">
            <button class="btn-submit5 w20" name="deleteListing" id="deleteListing" value="deleteListing" type="button"  onClick="window.open('/relatedData/relatedData/index/12/<?php echo $type_id; ?>/<?php echo $listing_type; ?>');">
               <div class="btn-submit5"><p class="btn-submit6">Set Related Q's</p></div>
            </button>
         </div>
         <?php } ?>

         <?php } ?>
         
         <?php if(($details['status'] == 'draft') || ($details['status'] == 'queued')){ ?>
         <div class="buttr2">
             <button class="btn-submit5 w20" name="publishListing" id="publishListing" value="publishListing" type="button" onClick="window.open('/enterprise/ShowForms/fetchPreviewPage/<?php echo $listing_type; ?>/<?php echo $type_id; ?>','hurray','fullscreen=yes,menubar=yes,status=yes,location=yes,toolbar=yes,scrollbars=yes,directories=yes'); return false;">
                <div class="btn-submit5"><p class="btn-submit6">See Preview Page</p></div>
            </button>
         </div>
         <?php } ?>

         <?php if(($details['status'] == 'draft') || 
	          ($details['status'] == 'queued' && $validateuser[0]['usergroup'] == 'cms')
                 ){ ?>
         <div class="buttr2">
             <button class="btn-submit5 w20" name="publishListing" id="publishListing" value="publishListing" type="button" onClick="window.location='<?php echo site_url(); ?>enterprise/ShowForms/showPreviewPage/<?php echo ($listing_type=='course')?$details['institute_id']:$type_id; ?>'" >
                <div class="btn-submit5"><p class="btn-submit6">Publish Listing</p></div>
            </button>
         </div>
         <?php } ?>
         
         <?php if(($details['status'] == 'draft') && FALSE){ //Hiding Delete Draft Button?>
         <div class="buttr2">
             <button class="btn-submit5 w20" name="reportAbuse" id="deleteDraft" value="deleteDraft" type="button"  onClick="deleteDraft('<?php echo $listing_type; ?>','<?php echo $type_id; ?>','draft');" >
                <div class="btn-submit5"><p class="btn-submit6">Delete Draft</p></div>
            </button>
         </div>
         <?php } ?>

         <?php if(($details['status'] == 'queued') && FALSE){ // Hiding Delete Queue Button?>
         <div class="buttr2">
             <button class="btn-submit5 w20" name="reportAbuse" id="deleteQueued" value="deleteDraft" type="button"  onClick="deleteDraft('<?php echo $listing_type; ?>','<?php echo $type_id; ?>','queued');" >
                 <div class="btn-submit5"><p class="btn-submit6">Delete Queued</p></div>
            </button>
         </div>
         <?php } ?>
         
         <?php if(strtolower($listing_type) == 'institute'){?>
         <div class="buttr2">
         <button class="btn-submit5 w20" name="addNewCourse" id="addNewCourse" value="allNewCourse" type="button"  onClick="window.location.href='/enterprise/ShowForms/showCourseForm/<?php echo $type_id; ?>';">
               <div class="btn-submit5"><p class="btn-submit6">Add New Course </p></div>
            </button>
         </div>
         <?php } ?>

         <?php if(strtolower($listing_type) == 'institute' && $validateuser[0]['usergroup'] == 'enterprise'){?>
         <div class="buttr2">
         <button class="btn-submit5 w20" name="alumfeedback" id="alumfeedback" value="alumfeedback" type="button"  onClick="window.location.href='/enterprise/Enterprise/index/30/<?php echo $type_id; ?>';">
               <div class="btn-submit5"><p class="btn-submit6">Alumni Feedbacks</p></div>
            </button>
         </div>
         <?php } ?>

         <?php // if($validateuser[0]['usergroup'] == 'cms'){ ?>
         <?php switch($listing_type){
               case "course":

               echo '<div class="buttr3">
                  <!--<button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/Enterprise/cmsEditCourse/'.$type_id.'\'">-->
                  <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/ShowForms/showCourseEditForm/'.$type_id.'\'">
                     <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit this Course</p></div>
                  </button>';
		if($validateuser[0]['usergroup'] == 'cms') { 
		  // echo '<button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/ShowForms/upgradeListingClientSearchForm/'.$type_id.'/course\'">
    //                  <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Upgrade this Course</p></div>
    //               </button>';
			if ($status == 'live') {
				echo '<button class="btn-submit7 w7" name="copyThisCourse" id="copyThisCourse" value="copyThisCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/ShowForms/selectInstituteForCopyCourse/'.$type_id.'\'">
                     <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Copy this Course</p></div>
                  </button>';
			}	
		}
               echo '</div>';
               break;

               case "notification":

               echo '<div class="buttr3">
                  <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/Enterprise/editNotification/'.$type_id.'\'">
                     <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit this Notification</p></div>
                  </button>
               </div>';
               break;

               case "scholarship":

               echo '<div class="buttr3">
                  <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/Enterprise/cmsEditScholarship/'.$type_id.'\'">
                     <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit this Scholarship</p></div>
                  </button>
               </div>';
               break;

               case "institute":

               echo  '<div class="buttr3">
                   <!--<button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/Enterprise/cmsEditInstitute/'.$type_id.'\'">-->
                   <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/ShowForms/editInstituteForm/'.$type_id.'\'">
                       <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit this Institute</p></div>
                  </button>';
		
		if($validateuser[0]['usergroup'] == 'cms') { 
		   	/*echo '<button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location=\''.site_url().'/enterprise/ShowForms/upgradeListingClientSearchForm/'.$type_id.'/institute\'">
                       <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Upgrade this Institute</p></div>
                  </button>';*/
		}
				echo '<button class="btn-submit7 w7" name="viewMediaPage" id="viewMediaPage" value="viewMediaPage" type="button" onClick="window.location=\''.site_url().'/enterprise/ShowForms/showMediaInstituteForm/institute/'.$type_id.'/2\'">
            	  <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add Photos & Videos</p></div>
                  </button>';
				echo '<button class="btn-submit7 w7" name="viewAlumniFeedback" id="viewAlumniFeedback" value="viewAlumniFeedback" type="button" onClick="window.location=\''.site_url().'/enterprise/Enterprise/index/30/'.$type_id.'\'">
            	  <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">View Alumni Feedback</p></div>
                  </button>';
				echo '<button class="btn-submit7 w7" name="viewCourseOrder" id="viewCourseOrder" value="viewCourseOrder" type="button" onClick="showCourseOrderOverlay('.$type_id.')">
            	  <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Set Course Order</p></div>
                  </button>';
				  if($validateuser[0]['usergroup'] == 'cms') { 
				  echo '<button class="btn-submit7 w7" name="viewCourseOrder" id="viewCourseOrder" value="viewCourseOrder" type="button"  onClick="window.location=\''.site_url().'/enterprise/Enterprise/deleteMultipleCourses/'.$type_id.'/\'">
            	  <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete Multiple Courses</p></div>
                  </button>';
				  }
                  echo '</div>';
               break; 

            }
      // }
         ?>
         <br clear="left" />
      </div>
   </div>
   <div class="clear_L"></div>
