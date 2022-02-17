<?php
if($formName == ENT_SA_FORM_EDIT_RMS_UNIVERSITY_COUNSELLOR_MAPPING) {
		$pageTitle = "Edit Mapping";
		
		$displayData["breadCrumb"] = array(
						array("text" => "All Mapped Counsellor", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES ),
						array("text" => "Edit Mapping", "url" => "")
						);
		$displayData["pageTitle"] = $pageTitle;
		$sectionTitle = "Edit University Counselor Mapping";
		$displayData["lastUpdatedInfo"] = array(
						"date" => $last_modified,
						"username" => $modifiedname
						);
		
		$formName = ENT_SA_FORM_EDIT_RMS_UNIVERSITY_COUNSELLOR_MAPPING;
		$displayStatus = "block";
		$actionFunction = 'editUniversityCounsellorMapping';
		$universityBoxDisabled='disabled="disabled"';
		$searchBoxbuttonFunction='';
		if($sessionDate=='' || $sessionDate=='00/00/0000'){
		  $sessionDate ='DD/MM/YYYY';
		}
		$sessionDateEditableFlag = true;
		
} else {
		$displayData["breadCrumb"] = array(
						array("text" => "All Mapped Counsellor", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES ),
						array("text" => "Create New Mapping", "url" => "")
						);
		$displayData["pageTitle"] = "Create Mapping";
		$sectionTitle = "Create University Counselor Mapping";
		$formName = ENT_SA_FORM_ADD_RMS_UNIVERSITY_COUNSELLOR_MAPPING;
		$displayStatus = "none";
		$actionFunction = 'saveUniversityCounsellorMapping';
		$guideId= '';
		$sessionDate ='DD/MM/YYYY';
		$university_id='';
		$university_name='';
		$RMSType='';
		$universityBoxDisabled='';
		$searchBoxbuttonFunction="validateUniversityId(this,'universityId0');";
		$sessionDateEditableFlag = true;
		
}



if(is_array($counsellorList))
{
		
  $counsellorDropDownHtml .= '<option value="" >Counsellor Name</option>';
  $disabledSelectBox = '';
  foreach($counsellorList as $counsellor) {
	$selected = "";	
	if($counsellorID == $counsellor['counsellor_id'])
	{
          $selected = 'selected="selected"';
	  $disabledSelectBox = 'disabled="disabled"';
	  $counsellorName = $counsellor['counsellor_name'];
	}
    $counsellorDropDownHtml .=  '<option value="'.$counsellor['counsellor_id'].'" '.$selected.'>'.$counsellor['counsellor_name'].'</option>';
  }
  $counsellorDropDownHtml .= '';	

}
?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
            <?php		
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
             ?>
         <form name="form_<?=$formName?>" id="form_<?=$formName?>" action="<?=ENT_SA_CMS_PATH?><?= $actionFunction;?>"  enctype="multipart/form-data" method="post">        
            <?php if($mappingID !=''){?>
	    <input type="hidden" name="mappingID" value="<?= $mappingID?>" />
	    <input type="hidden" name="created" value="<?= $created?>" />
	    <input type="hidden" name="created_by" value="<?= $created_by?>" />
	    <?php }
	        if($counsellorID !=''){
	    ?>
	    <input type="hidden" name="counsellorName" value="<?= $counsellorID?>" />
	    <?php } ?>
	    <div class="cms-form-wrapper clear-width">
                <div class="clear-width">
                    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i><?= $sectionTitle;?></h3>
                    <div class="cms-form-wrap" style="margin-bottom:0">
                        <ul>
                            <li style="margin-bottom:0;">
                                <label>Select Counselor* : </label>
                                <div class="cms-fields">
                                  <div class="add-more-sec">
                                    <select class="universal-select cms-field" name="counsellorName" id="counsellorName" validationType="select" required=true caption="Counsellor Name" <?= $disabledSelectBox?>>
						<?= $counsellorDropDownHtml;?>
                                    </select>
				    <div style="display: none" class="errorMsg" id="counsellorName_error"></div>
                                  </div>
                                </div>
                            </li>
                         </ul>
		               <div class="cms-form-wrap universitySection" style="margin:0">
				    <div class="add-more-sec2 clear-width">
					<input type="hidden" name="sectionindex[]" value="0" />  
					<ul>
					  <li>
							<label>University ID* : </label>
							<div class="cms-fields">
							  <div class="add-more-sec clear-width" style="margin-bottom:0">
								<input value="<?= $university_id;?>" type="hidden" name="universityId[]" id="universityId0" validationType="numeric" maxlength="10"  minlength="1" tooltip="" caption="University ID" required=true />
								<input value="<?= $university_id;?>" class="universal-txt-field cms-text-field flLt" name="universityIdBox[]" type="text" style="width:150px !important;" id="universityId0Box" <?= $universityBoxDisabled;?> autocomplete="off" />
								<a class="edit-search-box flLt" href="javaScript:void(0);" style="margin-right:5px;" onclick="<?= $searchBoxbuttonFunction;?>"><i class="abroad-cms-sprite rank-search-icon"></i></a>
								<span style="padding-top:5px; display:block;"><?= $university_name;?></span>
							  </div>
							   <div style="display: none" class="errorMsg" id="universityId0_error"></div>
							</div>
					  </li>
					  <li>
							<div class="cms-fields">
								<div class="add-more-sec clear-width" style="margin-bottom:0">
								  <input id="sessionDetail0" name="sessionDetail[]" type="hidden" value="<?= ($sessionDetails!='')?$sessionDetails:'0'?>">
								  <input class="flLt" id="universitySessionDetail0" type="checkbox" onclick="changeUniversitySessionDetails(this);">
								  <label class="flLt" style="padding:2px; text-align:left;"><strong>Session from university</strong></label>
								</div>
							</div>
                        </li>
					</ul>  
				     <ul style="display: none;" class="sessionDetailContainer">
						<li>
						  <label>Up Coming Session::* : </label>
						  <div class="cms-fields">
							<div class="add-more-sec clear-width" style="margin-bottom:0">
							  <input class="universal-txt-field cms-text-field flLt" type="text" name="sessionDate[]" id="sessionDate0" value="<?=$sessionDate;?>" readonly style="width:150px !important; margin-right:5px;" validationType="date" tooltip="" caption="valid start date" /><i class="abroad-cms-sprite calendar-icon" name="sessionDate_img" id="sessionDate0_img" style="top:3px;" onClick="sessionDate(this)"></i>
							</div>
							<div style="display: none" class="errorMsg" id="sessionDate0_error"></div> 	
						  </div>
					    </li>
						<li>
                        <label>Photo of university rep: </label>
                        <div class="cms-fields">
						  <input validationType="file" type="file" id="repImg0_<?=$formName?>" name="repImg_<?=$formName?>[]" onblur = "showRequiredFileError('repImg_<?=$formName?>', 'Please select a image File');" onchange = "showRequiredFileError('repImg_<?=$formName?>', 'Please select a image File');" caption="Admission Guide Image" required2=true  />
						  <?php if($universityRepImageUrl !=''){?>
						  <span id="imgContainer"><a target="_blank" href="<?= $universityRepImageUrl;?>">View Image</a>
							  <a href="javascript:void(0);" class="remove-link-2" onclick="$j('#repImg_<?=$formName?>_hidden').val(''); $j('#imgContainer').fadeOut('slow');"><i class="abroad-cms-sprite remove-icon"></i>Remove Image</a>
							  </span>
						  <input type="hidden" value="<?= $universityRepImageUrl;?>" id="repImg_<?=$formName?>_hidden" name="repImg_<?=$formName?>_hidden">
						  <?php }?>
						  <div style="display: none" class="errorMsg" id="repImg0_<?=$formName?>_error"></div>					  
                          <div style="font-size:11px;padding:16px 0 0 6px;color:#666;">Size of photos should be 172 X 115px</div>
                        </div>
                    </li> 
                    </li>
                    <li>
                        <label>Name of university rep:</label>
                        <div class="cms-fields">
                          <div class="add-more-sec clear-width" style="margin-bottom:0">
                            <input value="<?= htmlentities($universityRepName);?>" class="universal-txt-field cms-fields cms-text-field flLt" name="universityRepName_<?=$formName?>[]" id="universityRepName_<?=$formName?>0" type="text" style="margin-right:5px;" maxlength=100 validationType="str" caption="Name of university rep"/>
                         </div>
						 <div style="display: none" class="errorMsg" id="universityRepName_<?=$formName?>0_error"></div>
                        </div>
                    </li>
                    <li>
                        <label>Designation university of rep:</label>
                        <div class="cms-fields">
                          <div class="add-more-sec clear-width" style="margin-bottom:0">
                            <input value="<?= htmlentities($universityRepDesignation);?>" class="universal-txt-field cms-fields cms-text-field flLt" name="universityRepDesignation_<?=$formName?>[]" id="universityRepDesignation_<?=$formName?>0" type="text" style="margin-right:5px;" maxlength=100 validationType="str" caption="Designation university of rep"/>
                          </div>
						  <div style="display: none" class="errorMsg" id="universityRepDesignation_<?=$formName?>0_error"></div>
                        </div>
                    </li>
                    <li>
                        <label>About this session:</label>
                        <div class="cms-fields">
						  <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Session Details" id="aboutSession0_<?=$formName?>" name="aboutSession_<?=$formName?>[]"><?= $aboutSession;?></textarea>
						  <div style="display: none" class="errorMsg" id="aboutSession0_<?=$formName?>_error"></div>
                        </div>
                    </li>
					</ul>
					<ul>
					<li>
					<label style="padding:0;"></label>
					<div class="cms-fields">
					  <div class="remove-button"></div>
					</div>
					</li>
					    
					 </ul>
				    </div>
				</div>  
				<?php
				if($formName == ENT_SA_FORM_ADD_RMS_UNIVERSITY_COUNSELLOR_MAPPING) {
				?>
				<div class="button-wrap" id="addmoreSection">
				    <a onclick="cloneUniversitySection();" href="javascript:void(0);">[+] Add Another University</a>	
				</div>
				<?php }?>
		        </div>
		  </div>  
             </div>
		<input type="hidden" name="formName" value="<?= $formName;?>" />	 
        <div class="button-wrap">
                <a href="javascript:void(0);" onclick="saveUniversityCounsellorMappingForm(this, '<?=$formName?>')" id="bttnSavePublish" name="bttnSavePublish" class="orange-btn">Save & Publish</a>
                <a href="javascript:void(0);" onclick="cancelAction()" class="cancel-btn">Cancel</a>
            </div>
        <div class="" style="margin-top: 45px;"></div>
	<?php
	if($counsellorID != '') {
	?>
	<div class="mapped-univ-table">
        	<h4 class="ranking-head" style="margin-left:0;word-wrap:break-word;">All Universities Mapped to <?= $counsellorName;?> : Total <?= $mappedUniversityCount;?></h4>
        	<table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure" style="margin:15px 0 0;">
                    <tr>
                         <th width="5%" align="center">S.No.</th>
                         <th width="30%"><span class="flLt">Mapped University Name</span></th>
                         <th width="13%"><span class="flLt">Up coming session</span></th>
                         <!--<th width="13%"><span class="flLt">End Date</span></th>
                         <th width="10%"><span class="flLt">RMS Type</span></th>-->
                         <th width="20%"><span class="flLt">Response Count</span></th>
                         <th width="21%"><span class="flLt">Status</span></th>
                    </tr>
		<?php
		$count =0;
		foreach($mappedUniversityList as $mappedTuple)
		{
		   $count++;
		?>    
                    <tr>
                    	<td align="center"><?= $count?>.</td>
                        <td><p><?= htmlentities($mappedTuple['UniName']);?></p><div class="edit-del-sec">
				<?php if($mappedTuple['status']=='live'){?>		
				<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_RMS_UNIVERSITIES ?>/<?= $counsellorID.'/'.$mappedTuple['id'];?>">Edit</a>&nbsp;&nbsp;
				<a href="javaScript:void(0)" onclick="confirmDeleteMapping(<?= $mappedTuple['id'];?>);">Delete</a>&nbsp;&nbsp;
				<?php } ?>
				<!--<a href="#">Delete</a></div>--></td>
                        <td><p><?= ($mappedTuple['sDate']=='')?'--':date("d M y",strtotime($mappedTuple['sDate']));?></p></td>
                        <!--<td><p><?php //date("d M y",strtotime($mappedTuple['eDate']));?></p></td>
                        <td><p><?php //$mappedTuple['RMSType'];?></p></td>-->
                        <td><p>Total No of Response: <?= $mappedTuple['count'];?></p>
				<?php if($mappedTuple['count'] >0 && $mappedTuple['status']=='live'){?>
				<a href="/listingPosting/AbroadListingPosting/exportRmsCounsellorResponse/<?= $mappedTuple['id'];?>" class="export-btn flRt">Export ></a>
				<?php }?>
			</td>
            <td>
			  <span style="color: green;"><?= ($mappedTuple['status']=='live')?"Active":'';?></span>
			  <span style="color: red;"><?= ($mappedTuple['status']=='deleted')?"Deleted":'';?></span>
			</td>
            </tr>
		<?php }?>    
                </table>
    	</div>
       <?php }?>
     </form>   
    </div>
<script>
//window.onbeforeunload =confirmExit; 
    var preventOnUnload = false;
    var saveInitiated = false;  
		function confirmExit()
		{
			if(preventOnUnload == false)
				return 'Any unsaved change will be lost.';
		}     
    
    function startCallback() {
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response) {
        saveInitiated = false;
        // check response
        var respData;
        if (response != 0) {
            respData = JSON.parse(response);
        }
        
        if (typeof respData != 'undefined' && (typeof respData.already != 'undefined' || typeof respData.Fail != 'undefined' || typeof respData.fileError != 'undefined'))
		{
            preventOnUnload = true;
			if (typeof respData.already != 'undefined')
			{
			  for(var x=0;x<respData.already.length;x++)
			  {
				$j('#universityId'+respData.already[x]+'_error').html('University is already assign to counselor.').show();		
			  }
			}
			if (typeof respData.fileError != 'undefined')
			{
			  for(var x=0;x<respData.fileError.length;x++)
			  {
				var counter = $j('[name="sectionindex[]').eq(x).val();
				console.log(counter);
				if (typeof respData.fileError[x].Fail != 'undefined') {
				  $j("#repImg"+counter+"_"+formname+"_error").html(respData.fileError[x].Fail).show();
				}
				
			  }
			}
			
        }
        else{
		if(respData.Edit==true)
		{
			alert("Mapping updated successfully.");	
		}else{
			alert("Mapping added successfully.");	
		}
		preventOnUnload = true;
		window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES?>";
        }
    }
	
    function initFormPosting() {
                    AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }
    
    if(document.all) {
            document.body.onload = initFormPosting;
    } else {
            initFormPosting();
    }
    function confirmRedirection()
    {   var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
        if (choice) {
            preventOnUnload = true;
            //window.onbeforeunload = null;
            window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES?>";
        }
        else{
            preventOnUnload = true;
        }
    }		
		
function cancelAction() {
		if (confirm("Are you sure you want to cancel? All data changes will be lost.")) {
				window.location.href = "<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES?>";
		}
}

var universitySectionCount = 1;
function cloneUniversitySection() {
        
	if ($j('.universitySection').length < 10) {		
	var cloneDiv = $j('.universitySection:first').clone(true,true);
	cloneDiv.find('[name="universityId[]"]').val('');
	cloneDiv.find('[name="universityIdBox[]"]').val('').attr('disabled',false);
	cloneDiv.find('[name="sectionindex[]"]').val(universitySectionCount);
	cloneDiv.find('.edit-search-box').find('i').removeClass('edit-icon').addClass('rank-search-icon');
	cloneDiv.find('.edit-search-box').next('span').html('');
	var removeButtonHtml = '<a href="javascript:void(0);" style="margin:0px;" class="remove-link-2" onclick="removeUniversityElement(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a><div id="" class="errorMsg" style="display: none"></div>';
	cloneDiv.find('.remove-button').html(removeButtonHtml);
	cloneDiv.find('.errorMsg').hide();
	cloneDiv.find('.sessionDetailContainer').hide();
	cloneDiv.find('textarea').text('');
	
        var mainHtml = cloneDiv.html();

	var newSessionDateId = 'sessionDate'+universitySectionCount;
	var newuniversityId = 'universityId'+universitySectionCount;
	var newSessionhiddenId  = 'sessionDetail'+universitySectionCount;
	var newAboutSessionId = 'aboutSession'+universitySectionCount+'_';
	var newRepImgId = 'repImg'+universitySectionCount;
	
	mainHtml = mainHtml.replace(/sessionDate0/g,newSessionDateId);
	mainHtml = mainHtml.replace(/universityId0/g,newuniversityId);
	mainHtml = mainHtml.replace(/aboutSession0_/g,newAboutSessionId);
	mainHtml = mainHtml.replace(/repImg0/g,newRepImgId);
	
	
	cloneDiv.html(mainHtml);
	cloneDiv.show();
	cloneDiv.insertBefore($j('#addmoreSection'));
	
	var textareaId = newAboutSessionId+formname;
	tinymce.EditorManager.execCommand('mceAddEditor', false, textareaId);
	universitySectionCount++;
	}else{
		alert('you can add max 10 university at a time');
	}
}

function removeUniversityElement(elem)
{
	$j(elem).parents('.universitySection').fadeOut('slow',function(){
            $j(elem).parents('.universitySection').remove();
        });		
}

function validateUniversityId(obj,id)
{
    
    if ($j(obj).find('i').hasClass('edit-icon'))
    {
       $j(obj).find('i').removeClass('edit-icon').addClass('rank-search-icon');
       $j('#'+id+'_error').html('').hide();
       $j(obj).next('span').html('');
       $j('#'+id).val('');
       $j('#'+id+'Box').attr('disabled',false);
    }else{
		
     var universityId = $j('#'+id+'Box').val();
     var numericRegx = /^(\d)+$/;
     if(!numericRegx.test(universityId)){
	$j('#'+id+'_error').html('Please enter a numeric value.').show();
    	return;
     }
     var alreadyFlag =false;
     //Check for already added University
	$j('[name="universityId[]"]').each(function(){
		if ($j(this).val()== universityId) {
		   $j('#'+id+'_error').html('university already mapped.').show();
		   alreadyFlag = true;
		   return;
		}
        });
	if (alreadyFlag ==true) {
		return ;
	}
        		
	var ajaxURL = "/listingPosting/AbroadListingPosting/findUniversityName";
	   $j.ajax({
		 type: 'POST',
		 url : ajaxURL,	
		 data: { 
		        'listing_id'	: universityId, 
		        'listing_type'	: 'university'
		    }, 
		 success : function(res){
			var responseArray = eval('('+res+')');
			 if(responseArray.errorFlag==1)
			 {
		             $j('#'+id+'_error').html(responseArray.errorMsg).show();	
			 }else
			 {
			    $j('#'+id+'_error').html('').hide();
			    $j(obj).next('span').html(responseArray.name);
			    $j('#'+id).val(responseArray.universityId);
			    $j(obj).find('i').removeClass('rank-search-icon').addClass('edit-icon');			   
			    $j('#'+id+'Box').attr('disabled',true);
		         }	
				
		 }
	  });
        }  
}



function showRequiredFileError(str,msg)
{
    var errorflag = false;
     if($j('#'+str).val()=='' && $j('#'+str+'_hidden').val()=='')
     {
	$j('#'+str+'_error').show().html(msg);
	errorflag =true;
     }else{
	$j('#'+str+'_error').hide().html('');	
     }
     return errorflag;
}

function checkFileError()
{
   var errorFlag = false; 		
   if(showRequiredFileError('repImg_<?=$formName?>', 'Please select a image File'))
   {
	errorFlag = true; 	
   }
   return errorFlag;
}

function changeUniversitySessionDetails(obj)
{
  var container = $j(obj).parents('ul').next().eq(0);
  if ($j(obj).prop('checked')==true){
	container.show();
	container.find('[name="sessionDate[]"]').eq(0).attr('required',true);
	$j(obj).parents('ul').find('[name="sessionDetail[]"]').eq(0).val(1);
  }else{
	container.hide();
	container.find('[name="sessionDate[]"]').eq(0).attr('required',false);
	$j(obj).parents('ul').find('[name="sessionDetail[]"]').eq(0).val(0);
  }
}

function sessionDate(thisObj) {
	disDate = new Date();
	calMain = new CalendarPopup('calendardiv');
	var passedYear = new Date().getFullYear() - 10;
	calMain.select($j(thisObj).prev()[0] ,$j(thisObj).attr("id"),'dd/mm/yyyy');
	return false;
}

function confirmDeleteMapping(mappingId)
    {
		var choice = confirm("Are you sure you want to delete this mapping?");
        if (choice) {
            preventOnUnload = true;
            var ajaxURL = "/listingPosting/AbroadListingPosting/deleteRMSUniversityCounsellorMapping";
			$j.ajax({
			  type: 'POST',
			  url : ajaxURL,	
			  data: { 
					 'mappingId'	: mappingId
				 }, 
			  success : function(res){
				alert(res);
				window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES?>";
			  }
		   });
			
        }
    }	


var showUniversitySession = <?= ($sessionDetails == "1")?"1":"0"?>;
</script>