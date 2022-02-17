<?php
$codeHeading = '';
$courseCodeFlag = false;
if($examinationName=='KEAM'){
$codeHeading = 'Institute-Course code';
}else{
$codeHeading = 'CSAB course code';
$courseCodeFlag = true;
}
foreach($branchInformation as $key=>$branchObj){ $start++;
		$courseTypeHtml = "";
         if($branchObj->getShikshaCourseId()) {
             $course = $courseRepository->find($branchObj->getShikshaCourseId());
             
             if($validateuser != 'false') {
             	if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
             		if(is_object($course)){
             			if($course->isPaid()){
             				$courseTypeHtml = '<label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label>';
             			}else{
             				$courseTypeHtml = '<label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label>';
             			}
             		}
             	}
             }
              
         }
	     if($branchObj->getShikshaCourseId()){
			$courseUrl = $course->getURL();
			$type = "internal";
	     }
             else{
			$courseUrl = $branchObj->getInstCourseLink();
                        if(empty($courseUrl) || $courseUrl == 'NULL')
                        {
                                $courseUrl = $branchObj->getInstLink();
                        }
			$type = "external";
	     }
          
	    $instUrl = '';

	   	$courseId = $branchObj->getShikshaCourseId();
	   	if($courseId > 0 && $courseId !='')
	   	{
		   	$courseObj = $courseRepository->find($courseId);
			$instId = $courseObj->getInstituteId();		
			$instObj = $instituteRepository->find($instId);
			$instUrl = $instObj->getURL();
		}
            if ($branchObj->getShikshaCourseId() && isset($_COOKIE["applied_".$course->getId()]) && ($validateuser != "false")) {
				   $class = "gray-bro-btn bro-btn-disable";
				   $brochureDisable = "true";
				   $style="display: block; margin: 8px 0;";
				   echo "<script>brochureDisable = 'true';</script>";
				   $aa = "return false";      
	    }
	    else{
			$aa = "";
			$style="display: none;margin: 8px 0;";
			$class  = "gray-bro-btn";
			$brochureDisable = "false";
			echo "<script>brochureDisable = 'false';</script>";
	    }
			?>
			  <tr class="<?php if($start%2==0) echo "alt-rowbg";?>">
                           
			
      <?php if($tab==3){?>
       <td <?php echo $tdStyle;?>>
              <?php if($courseUrl=='NULL'){ ?>
			<?=$branchObj->getBranchName();?>
       <?php }else{ ?>
<a href="<?=addhttp($courseUrl);?>"  <?php if($type == "external"): ?>target="_blank" rel="nofollow" <?php endif;?> onclick="trackEventByGA('Branchtabclick','BRANCH_TAB');"><?=$branchObj->getBranchName();?></a>
<?php } ?>
<?php echo $courseTypeHtml;?>
<?php if(!$courseCodeFlag){ ?>
      <span><?php echo $codeHeading; ?>: <?php echo $branchObj->getCourseCode(); ?></span>
<?php } ?>      
      </td>
      <td <?php echo $tdStyle;?>>
	    <?php if($inputData['round']=='all'){ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?=$branchObj->getCollegeName().', '.$branchObj->getCityName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
	    <?php }else{ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?=$branchObj->getCollegeName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
	    <?php } ?>
	       
			<?php
	    
	    if($branchObj->getShikshaCourseId()  && !$print) { ?>
			<?php if($validateuser != "false"){ ?>
			
			<a id="courseid_<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?=$class?>"
			  onmouseover= "activateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
		          onmouseout= "deactivateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
			  
		       	 onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			 responseForm.showResponseForm('<?php echo $course->getId()?>','CollegePredictor','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{}); <?php }?>; trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?=$branchObj->getNumberOfRound();?>')" >
						Download E-Brochure
			</a>
				        
			<?php } else{  ?>
			
			  <a id="courseid_<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?=$class?>"
			  onmouseover= "activateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
			 onmouseout= "deactivateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
			 
			  onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			 responseForm.showResponseForm('<?php echo $course->getId()?>','CollegePredictor','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{}); <?php }?> ; trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?=$branchObj->getNumberOfRound();?>')">
			 
						Download E-Brochure
			</a>
			<?php }?>
					    
			<div id="apply_confirmation_<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>" style="<?=$style;?>">
						       <i class="predictor-sprite tick-icon" style="margin: 0; float: left"></i>
						       <p style="margin:0 0 0 23px;float: none; color: #4EC42E; font-size:12px;">E-brochure successfully mailed.</p>
			</div>
	<?php } ?>
	    
	    
		       <!-----------------Add--to-compare--tool--------------------------->
		       <?php
		       if($branchObj->getShikshaCourseId() && $print != 1)
		       {
				if($course->isPaid() || $course->getBrochureURL() !='')
				{
				   $getInstituteImage = $instituteRepository->find($course->getInstituteId());
		       ?>
			<p class="flRt">
<input onclick="myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');"type="checkbox" name="compare" id="compare<?=$course->getInstituteId();?>-<?=$course->getId()?>" class="addToCompareOnCollegePredictorCheckBox compare<?=$course->getInstituteId();?>-<?=$course->getId()?>" value="<?php echo $course->getInstituteId().'::'.' '.'::'.($getInstituteImage->getHeaderImage()?$getInstituteImage->getHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($course->getInstituteName())).', '.$branchObj->getCityName().'::'.$course->getId().'::'.$course->getURL();?>"/>
<a style="font-size:13px;"  href="javascript:void(0);" onclick="trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');
myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});
	return false;" id="compare<?php echo $course->getInstituteId();?>-<?=$course->getId()?>lable"
 class="addToCompareOnCollegePredictor compare<?php echo $course->getInstituteId();?>-<?=$course->getId()?>lable">Add to Compare</a>
			</p>
			<div style="display:none">
			<input type="hidden" name="compare<?php echo $course->getInstituteId();?>-<?=$course->getId()?>list[]"  value= "<?=$course->getId()?>" /></div>
			<?php  }
			}?> 
			<!--------------------compare tool--end--------------------------->
      </td>
      <?php }else{ ?>
    <td <?php echo $tdStyle;?>>
<?php if($inputData['round']=='all'){ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?=$branchObj->getCollegeName().', '.$branchObj->getCityName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
<?php }else{ ?>
<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?=$branchObj->getCollegeName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
	<?php } ?>
<?php if($branchObj->getShikshaCourseId() && !$print) { ?>
	    
			<?php if($validateuser != "false"){ ?>
			
			<a id="courseid_<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?=$class?>"
			  onmouseover= "activateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
		          onmouseout= "deactivateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
			  
		       	 onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			 responseForm.showResponseForm('<?php echo $course->getId()?>','CollegePredictor','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{}); <?php }?> ; trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?=$branchObj->getNumberOfRound();?>')" >
						Download E-Brochure
			</a>
				        
			<?php } else{ ?>
			
			  <a id="courseid_<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?=$class?>"
			  onmouseover= "activateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
			 onmouseout= "deactivateDownload('<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>');"
			 
			  onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			  responseForm.showResponseForm('<?php echo $course->getId()?>','CollegePredictor','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{}); <?php }?> ; trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?=$branchObj->getNumberOfRound();?>')">
			 
						Download E-Brochure
			</a>
			<?php }?>
					    
			<div id="apply_confirmation_<?=$branchObj->getShikshaCourseId(); ?>_<?=$branchObj->getNumberOfRound();?>" style="<?=$style;?>">
						       <i class="predictor-sprite tick-icon" style="margin: 0; float: left"></i>
						       <p style="margin:0 0 0 23px;float: none; color: #4EC42E; font-size:12px;">E-brochure successfully mailed.</p>
			</div>
	    <?php  } ?>
	    
	    
	              <!-----------------Add--to-compare--tool--------------------------->
		       <?php
		       if($branchObj->getShikshaCourseId() && $print != 1)
		       {
				if($course->isPaid() || $course->getBrochureURL() !='')
				{
				   $getInstituteImage = $instituteRepository->find($course->getInstituteId());
		       ?>
			<p class="flRt">
<input onclick="myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});
trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');" 
type="checkbox" name="compare" id="compare<?=$course->getInstituteId();?>-<?=$course->getId()?>" class="addToCompareOnCollegePredictorCheckBox compare<?=$course->getInstituteId();?>-<?=$course->getId()?>" value="<?php echo $course->getInstituteId().'::'.' '.'::'.($getInstituteImage->getHeaderImage()?$getInstituteImage->getHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($course->getInstituteName())).', '.$branchObj->getCityName().'::'.$course->getId().'::'.$course->getURL();?>"/>
<a style="font-size:13px;"  href="javascript:void(0);" onclick="
trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');
myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});
return false;" id="addToCompareOnCollegePredictor compare<?php echo $course->getInstituteId();?>-<?=$course->getId()?>lable" class="compare<?php echo $course->getInstituteId();?>-<?=$course->getId()?>lable">Add to Compare</a>
			</p>
			<div style="display:none">
			<input type="hidden" name="compare<?php echo $course->getInstituteId();?>-<?=$course->getId()?>list[]"  value= "<?=$course->getId()?>" /></div>
			<?php   }
			
			}?> 
			<!--------------------compare tool--end--------------------------->
			       
			       
</td>		       
   <td <?php echo $tdStyle;?>>
        <?php if($courseUrl=='NULL'){ ?>
	    <?=$branchObj->getBranchName();?>
       <?php }else{ ?>
	<a href="<?=addhttp($courseUrl);?>" <?php if($type == "external"): ?>target="_blank" rel="nofollow" <?php endif;?> onclick="trackEventByGA('Branchtabclick','BRANCH_TAB');"><?=$branchObj->getBranchName();?></a>
	<?php } ?>
 <?php echo $courseTypeHtml;?>
 <?php if(!$courseCodeFlag){ ?>
  <span><?php echo $codeHeading; ?>: <?php echo $branchObj->getCourseCode(); ?></span>
  <?php } ?>
   </td>
      <?php } ?>      <?php
      $roundNumArr = array('1'=>'Round 1','2'=>'Round 2','3'=>'Round 3','4'=>'Spot Round','5'=>'Extra Spot Round');
      ?>
                           	<?php if($inputData['round']=='all'){ ?>
						<td <?php echo $tdStyle;?>><?=$roundNumArr[$branchObj->getNumberOfRound()];?></td>	
				<?php }else{ ?>
						<td <?php echo $tdStyle;?>><?=$branchObj->getCityName();?></td>
				<?php } ?>
                               <td class="last-item" <?php echo $tdStyle;?>><?=$branchObj->getClosingRank();?></td>
                           </tr>
                        <?php }?>
