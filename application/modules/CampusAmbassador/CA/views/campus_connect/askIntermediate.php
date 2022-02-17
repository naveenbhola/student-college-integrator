<?php
$successMsgFlag = $_COOKIE['isUserRegistersForQuestionPosting'];
?>
<div class="managment-studies-box gap-top">
<form style="<?php echo (($successMsgFlag=='yes')?'display:none;':'');?>" action="/messageBoard/MsgBoard/askQuestionFromListing" id="postQuestionFromCC" onsubmit="return false;" novalidate="true" method="post">
	<h2>Ask Question</h2>
	<textarea validate="validateStr" default="Enter your question here..." name="questionText" minlength="2" caption="Question" maxlength="140" id="questionText" placeholder="Enter your question here..." autocomplete="off" required="true"></textarea>
	<div style="display:none">
		<div style="padding-left:3px; clear:both; display:block" id="questionText_error" class="errorMsg"></div>
	</div>
	<div style="width: 205px; float: left; margin-bottom: 5px;">
	<div class="form-element-container custom-dropdown">
		<select name="question_category" id="question_category" caption="Question Category" required="true" validate="validateSelect">
		<option value="" selected="selected"> Select Question Category </option>
		<option value="Admission & Eligibility">Admission & Eligibility</option>
		<option value="Placements">Placements</option>
		<option value="Campus Life">Campus Life</option>
		<option value="Course details">Course details</option>
		<option value="College details">College details</option>
		<option value="Others">Others</option>
		</select>
		
		
	</div>
	<div style="display:none">
			<div style="padding-left:3px; clear:both; display:inline-block; width: 205px;" id="question_category_error" class="errorMsg"></div>
		</div>
	</div>
	<div style="width: 205px; float: left; margin-left: 10px; margin-bottom: 5px;">
	<div class="form-element-container  custom-dropdown">
		<select name="course_selected" selected="selected" id="course_selected" caption="Course" required="true" validate="validateSelect">
			<option value="">Select Course</option>
		<?php
		foreach($courseIds as $courseId)
		{
			$courseObj = $courseRepository->find($courseId);
			?>
			<option value="<?=$courseId?>"><?=$courseObj->getName();?></option>
			<?php
		}
		?>
		</select>
		
	</div>
	<div style="display:none">
			<div style="padding-left:3px; clear:both; display:inline-block; width: 205px;" id="course_selected_error" class="errorMsg"></div>
		</div>
	</div>
	<div class="form-element-container-r"><input type="button" class="submit" value="Submit" id="postQuestionButton" onclick="postQuestionFromCCIntermediatePage()"></div>
	<input type="hidden" id="instituteIdForAskInstitute" value="<?=$instituteId?>" />
	<input type="hidden" id="categoryIdForAskInstitute" value="3" />
	<input type="hidden" id="locationIdForAskInstitute" value="2" />
	<input type="hidden" id="cc_page_name" value="CC_intermediate_page" />	
	<?php if(!empty($_COOKIE['crtrackingPageKeyId']))
			{
				$qtrackingPageKeyId=$_COOKIE['crtrackingPageKeyId'];
			}
	?>
     <input type="hidden" id="tracking_keyid" name="tracking_keyid" value="<?=$qtrackingPageKeyId?>">
</form>
<div class="successfully-ques-container" id="successfully-posted-container" style="<?php echo (($successMsgFlag=='yes')?'display:block;':'');?>">
	<h2><span>Your question has been successfully posted.</span></h2>
	<p>We will update you as soon as we get an answer from a Current Student of this college. <br>
	In the meantime, you can also...</p>
	<div class="successfully-ques-container-bootom-link ">
	<ul>
	<li><a href="javascript:void(0);" onclick="$j('#postQuestionButton').val('Submit'); $j('#postQuestionButton').attr('onclick','postQuestionFromCCIntermediatePage()'); $j('#successfully-posted-container').hide(); $j('#postQuestionFromCC').slideDown('slow'); setCookie('isUserRegistersForQuestionPosting','no',-1,'/',COOKIEDOMAIN);">Ask another question</a> |</li>
	<li> <a href="<?=$courseURL?>">View details of this college</a> |</li>  
	<li><a href="<?=SHIKSHA_HOME.'/mba/resources/ask-current-mba-students'?>">Ask question to other colleges</a></li>
	</ul>
	</div>
</div>
</div>
<style>
	.form-element-container{width:205px; float: left; margin:10px 10px 5px 0; }
	.form-element-container-r{width:205px; margin-top:10px; float: right;}
</style>
<?php
setcookie('isUserRegistersForQuestionPosting','',time()-3600,'/',COOKIEDOMAIN);
?>