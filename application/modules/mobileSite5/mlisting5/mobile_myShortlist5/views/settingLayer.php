<div class="mys-foternav mys-course-settings" id="shortlistRightLayer" style="display:none;">
	<ul>
		<?php if($checkForDownloadBrochure[$courseId]){?>
		<li><a href="javascript:void(0)" id="request_e_brochure<?php echo $courseId;?>" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'download_brochure', '<?php echo $courseId;?>'); $('#mys-overlay, #popupBasicBack ,#shortlistRightLayer').hide();responseForm.showResponseForm('<?php echo $courseId ;?>','NM_shortlist_REB','course',{'trackingKeyId': '254','callbackObj':'','callbackFunction': 'shortListDownLoadBrochureCallBack','callbackFunctionParams': {'courseId':'<?php echo $courseId;?>'}},{});"><i class="msprite f-download"></i>Request Brochure</a></li>
		<?php }?>
		<!-- <li><a><i class="msprite f-add"></i>Add to Compare</a></li> -->
		<?php 
				$last_date_apply = strtotime($courseWithOnlineForm['last_date']);
				$todyas_date     = strtotime(date('Y-m-d'));
				$form_expired    = 0;
				if($todyas_date > $last_date_apply) {
					$form_expired    = 1;
				}


		if(!empty($courseWithOnlineForm) && $form_expired == 0) {?>
		<li><a href="javascript:void(0)" id="startApp<?php echo $courseId;?>" onclick="emailResults('<?php echo $courseId;?>', '<?php echo base64_encode($instituteName) ?>', '<?php if($courseWithOnlineForm['externalURL']!=''){echo 'false';} else {echo 'true';} ?>');"><i class="msprite f-apply"></i>Apply Online</a></li>
		<?php } ?>
		<li><a href="javascript:void(0)" id="report_incorrect_box<?php echo $courseId;?>"><i class="msprite f-report"></i>Report Incorrect Data</a></li> 
		<li><a href="javascript:void(0)" id="deleteShortlist<?php echo $courseId;?>"><i class="msprite f-delete"></i>Delete</a></li>
	</ul>
</div>


    <div style="width:280px;display:none;" id="report_incorrect<?php echo $courseId;?>" class="mys-popup mys-logn mys-report">
    	<form id ="reportIncorrectForm" name="reportIncorrectForm" action=""  method="POST" enctype="multipart/form-data">
          <span class="mys-pop-title">
              <h1>Report incorrect data to shiksha</h1>
              <a class="mys-cross" id="report_incorrect_cross<?php echo $courseId;?>" onclick="reportIncorrectShortlistedClose(<?php echo $courseId;?>)" href="javascript:void(0)"><i class="msprite mys-close"></i></a>
          </span>
          <span class="mys-pop-mid">
              <textarea class="txtAreamsg" id="descriptionText<?php echo $courseId;?>" onblur="if(value=='') value = 'Description....';showReportIncorrectError(<?php echo $courseId;?>,false)" onfocus="if(value=='Description....') value = '';showReportIncorrectError(<?php echo $courseId;?>,false)"   value="">Description....</textarea>
              <p  class="errorMsg" style="display:none" id="error_div<?php echo $courseId;?>"></p>
          </span>

          <span class="mys-pop-botm">
              <a class="mys-blue-btn" id="submit_report_incorrect<?php echo $courseId;?>" onclick ="reportIncorrectShorlistedCollege(<?php echo $courseId;?>);" >SEND REPORT</a>
          </span>
        </form>
    </div>
