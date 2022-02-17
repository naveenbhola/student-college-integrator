<?php 
                $quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
                if(isset($validateuser[0]['cookiestr'])) {
                	$cookieStr = $validateuser[0]['cookiestr'];
                	$cookieArray = explode('|',$cookieStr);
                	$email = $cookieArray[0];
                	$firstname = htmlspecialchars($validateuser[0]["firstname"]);
                	$lastname = htmlspecialchars($validateuser[0]["lastname"]);
                	$mobile = htmlspecialchars($validateuser[0]["mobile"]);
                }else {
                	$email = "";
                	$firstname = "";
                	$lastname = "";
                	$mobile = "";
                }
				$tempJsArray = array('myShiksha','user');   
                
		$headerComponents = array(
                                                'css'   =>      array('campusAmbassador'),
                      							 'js' => array('common','facebook','ajax-api','imageUpload','campusAmbassador','CalendarPopup','onlinetooltip','CAValidations'),
                                                'jsFooter'=>    $tempJsArray,
                                                'title' =>      'Ask and Answer - Education Career Forum Community – Study Forum – Education Career Counselors – Study Circle -Career Counseling',
                                                'product'       =>'campusAmbassador',
                                                'showBottomMargin' => false,
												'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
				
                                        );
                $this->load->view('common/header', $headerComponents);


//$this->load->view('CA/autoSuggestorForInstitute');  
$this->load->view('common/calendardiv');

?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>
<div id="connect-wrapp">
	<h4>Tell us about yourself.</h4>
    <div class="title-shade"></div>
    
    <div id="left-col">
    <form id="CAProfileForm" action="/CA/CampusAmbassador/submitCAprofileData"  accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="CAProfileForm" id="CAProfileForm">
	<input name="userid" value="<?php echo $userId?>" type="hidden" />
	<input name="inst_url" id="inst_url" value="<?php echo $landingPageUrl;?>" type="hidden" />
	<input name="edit" value="<?php echo $edit;?>" type="hidden" id="formStatusCA"/>
	<input id="qualification_str" value="1" type="hidden" />
	<input name="uniqueId" id="uniqueId" value="<?php echo $id;?>" type="hidden" />
    <div id="form-section" class="form-section">
        	<h5>Personal Details</h5>
            
                <ul>
                    <li>
                        <div class="field-col">
                        	<label>Your Name</label>
                       		<input type="text" class="universal-txt-field"  value="<?php echo $firstname;;?>" onmouseover="showTipOnline('If your name is Ramesh Kumar Gupta, enter Ramesh as your first name',this);" onmouseout="hidetip();" id='quickfirstname_ForCA' name='quickfirstname_ForCA'  maxlength="50" minlength="1" validate="validateDisplayName" caption="First Name." required = "true" />
						    <div style="display:none;"><div class="errorMsg" id="quickfirstname_ForCA_error" style="*float:left"></div></div>
                        </div>
                        
                        <div class="field-col">
                        	<label>Your Last Name</label>
                        	<input type="text" class="universal-txt-field" value="<?php echo $lastname;?>" onmouseover="showTipOnline('If your name is Ramesh Kumar Gupta, enter Gupta as your last name',this);" onmouseout="hidetip();" id='quicklastname_ForCA' name='quicklastname_ForCA'  maxlength="50" minlength="1" validate="validateDisplayName" caption="Last Name." required = "true" />
						    <div style="display:none;"><div class="errorMsg" id="quicklastname_ForCA_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="field-col">
                        	<label>Email</label>
                        	<?php if($userId > 0):?>
                        	<input type="text" class="universal-txt-field" disabled="disabled" value="<?php echo htmlspecialchars($email);?>" onmouseover="showTipOnline('Please enter an email ID which you access most frequently. All updates will be sent on this ID',this);" onmouseout="hidetip();" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" blurMethod = "checkAvailability(this.value,'quickemail'); setTimeout('checkEmail()',1500); " />
                        	<?php else:?>
                        	<input type="text" class="universal-txt-field" value="<?php echo htmlspecialchars($email);?>" onmouseover="showTipOnline('Please enter an email ID which you access most frequently. All updates will be sent on this ID',this);" onmouseout="hidetip();" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" blurMethod = "checkAvailability(this.value,'quickemail'); setTimeout('checkEmail()',1500); " />
                        	<?php endif;?>
							<div style="display:none;"><div class="errorMsg" id="quickemail_error" style="*float:left"></div></div>
                        </div>
                        
                        <div class="field-col">
                        	<label>Mobile No</label>
                        	<input type="text" class="universal-txt-field" value="<?php echo $mobile;?>" onmouseover="showTipOnline('Please enter your correct Mobile Number. It will be used for all important communications with you',this);" onmouseout="hidetip();" id = "quickMobile_ForCA" name = "quickMobile_ForCA" validate = "validateMobileInteger" required = "true" maxlength = "10" minlength = "10" caption = "Mobile." />
							<div style="display:none;"><div class="errorMsg" id="quickMobile_ForCA_error" style="*float:left"></div></div>
                        </div>
                        
                    </li>
                    
                    <li>
                        <div class="field-col">
                        	<label>Profile Photo</label>
                        	<?php if(isset($imageURL)) {
                        			if($imageURL == '') {
                        				$url = "/public/images/photoNotAvailable.gif";
                        			}else {
                        				$url = $imageURL;
                        			}
                        		}
                        	?>
                        	<?php if(isset($imageURL)):?>
                        	<span>
                        	<a target="_blank" href="<?php echo $url;?>" style="display: block">View Profile Image</a>
                        	</span>
                        	<?php endif;?>
                        	<input type="file" name='userApplicationfile' onmouseover="showTipOnline('Please upload your colour, close-up up photo, preferably in formals',this);" onmouseout="hidetip();" id="Imagebox" value="<?php echo $url;?>" />
                        	<div style='display:none;'><div class='errorMsg' id= 'Imagebox_err' style="*float:left;"></div></div>
                        </div>
                    </li>
                </ul>
        </div>
        <?php if(!$edit):?>
        <div id="qualification_section">
	        <div id="form-section-1" class="form-section" style="margin-bottom:5px">
	        	<label class="qualification-label" id="qualification_text" >Current Qualification <span style="color:red;">*</span>
	        	<a id="remove_qual" onclick="remove();" href="javascript:void(0);" style="font-size:14px;display:none;">- Remove qualification</a></label>
				<div id="qualificationForm_1">
	                <ul>
      			  <li>
      			      <div class="field-col dummy_autosuggest" id="dummy_autosuggest_1">
      			      		<input type="text"  class="universal-txt-field" value="Type Institute name..." onmouseover="showTipOnline('As you type the name of the institute, relevant matches will be shown in drop-down. Please click on the name to select',this);" onmouseout="hidetip();" id="dummy_input"  onclick="showAutosuggest(1);" />
      			      		<div style="display:none;"><div class="errorMsg" id="institute_error_1" style="*float:left"></div></div>
      			      </div>
      			      
			          <div id="first_sibling_1" style="display:none">  
			                <div class="field-col" id="anaAutoSuggestor" style="position: relative;">
			                        <input type="text" name="keywordSuggest" id="keywordSuggest" onmouseover="showTipOnline('As you type the name of the institute, relevant matches will be shown in drop-down. Please click on the name to select',this);" onmouseout="hidetip();"  class="universal-txt-field" autocomplete="off" default="Type Institute name..." value="Type Institute name..." onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" validate="validateStr" minlength="1" maxlength="200" required="true" caption="Institute Name" />
			                        <div style="display:none;"><div class="errorMsg" id="keywordSuggest_error" style="*float:left"></div></div>
			                        <div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;"></div>
			                    </div>	
			                    <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
			           </div> 
			             
			             
			               
			             <input type="hidden" name="suggested_institutes[]" id="suggested_institutes_1" value="" /> 			  
                        <div class="field-col">
							<select class="universal-select" onmouseover="showTipOnline('Please click on your Institute’s location from the Drop-down menu',this);" onmouseout="hidetip();" id="location_1" name="location[]" onchange="loadCourses(1);" required="true" validate="validateSelect" caption="Location"><option value="">Location </option></select>
                    		<div style="display:none;" id="loc_main"><div class="errorMsg" id="location_1_error" style="*float:left"></div></div>  
                        </div>  
                        
                                        
     				</li>
     				<li>
     	                   	<div class="field-col">
								<select class="universal-select" onchange="checkCourses(1); getCourseCampusURL(this.value,this);" onmouseover="showTipOnline('Please click on your course name from the Drop-down menu',this);" onmouseout="hidetip();" name="course[]" id="course_1" required="true" validate="validateSelect" caption="Course"  ><option value="">Course</option></select>
								<div style="display:none;"><div class="errorMsg" id="course_1_error" style="*float:left"></div></div>
	                        </div>
	                    	
	                    	<div class="field-col" style="padding-top:8px">
	                        	<input type="checkbox" name="currently_pursuing[]" id="currently_pursuing_1" /> I am currently pursuing this course
	                        </div>
     				</li>	                    
	                    <li class="mt10">
	                    	<label class="period-label">Time Period</label>
				 			<div class="flLt" style="margin-right:20px;" id="from_time_1">From &nbsp;
				 			 <input type='text' class="universal-txt-field" name='fromDate[]' id='fromDate_1' style="width:75px; font-size:12px" readonly maxlength='10' validate="validateCaDate"  required="true" caption="From Date."  default = 'dd/mm/yyyy' value="dd/mm/yyyy" onfocus='checkTextElementOnTransition(this,"focus");' blurMethod='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fromDate_1'),'fromDate_1_dateImg','dd/MM/yyyy');" />&nbsp;
				 			 <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='fromDate_1_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fromDate_1'),'fromDate_1_dateImg','dd/MM/yyyy'); return false;" />
					  		<script>
								document.getElementById("fromDate_1").style.color = "#ADA6AD";
						    </script>
						    <div style="display:none;"><div class="errorMsg" id="fromDate_1_error" style="*float:left"></div></div>
	                    	</div>
	                    	
				 			<div class="flLt" style="margin-right:20px" id="to_time_1">To &nbsp;
				 			 <input type='text' class="universal-txt-field" name='toDate[]' id='toDate_1' style="width:75px; font-size:12px" readonly maxlength='10' validate="validateCaDate" required="true" caption="To Date."  default = 'dd/mm/yyyy' value="dd/mm/yyyy" onfocus='checkTextElementOnTransition(this,"focus");' blurMethod='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('toDate_1'),'toDate_1_dateImg','dd/MM/yyyy');" />&nbsp;
				 			 <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='toDate_1_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('toDate_1'),'toDate_1_dateImg','dd/MM/yyyy'); return false;" />
					  		<script>
								document.getElementById("toDate_1").style.color = "#ADA6AD";
						    </script>
						    <div style="display:none;"><div class="errorMsg" id="toDate_1_error" style="*float:left"></div></div>
	                    	</div>
	                    	 <div style="display:none;clear:left;"><div class="errorMsg" id="date_error_1" style="*float:left"></div></div>
	                 	</li>
	                    
	                    <li>
	                    	<div class="field-col">
	                        	<label>Eligibility</label>
	                        	<input id="ele_1" type="text" onmouseover="showTipOnline('Please enter academic requirements like Minimum percentage in 12th/Graduation etc',this);" onmouseout="hidetip();" class="universal-txt-field" value="" validate="validateStr" Caption="Eligibility"   name="eligibility[]" required="true" maxlength="100" minlength="1"  />
								<div style="display:none;"><div class="errorMsg" id="ele_1_error" style="*float:left"></div></div>
	                        </div>
	                        
	                        <div class="field-col">
	                        	<label>Selection process</label>
	                        	<input id="sel_1" type="text" onmouseover="showTipOnline('Please enter the selection process with name of entrance exams required if any and subsequent process like GD/PI etc',this);" onmouseout="hidetip();" class="universal-txt-field" value="" name="selection_process[]" validate="validateStr" Caption="Selection Process" maxlength="200" minlength="1" required="true"/>
	                        	<div style="display:none;"><div class="errorMsg" id="sel_1_error" style="*float:left"></div></div>
	                        </div>
	                    </li>
	                    
	                    <li>
	                    	<div class="field-col">
	                        	<label>Fees</label>
	                        	<input id="fees_1" type="text" onmouseover="showTipOnline('Please enter the total fees for the course including hostel fees etc',this);" onmouseout="hidetip();" class="universal-txt-field" value="" name="fees[]" validate="validateStr" Caption="Fees" maxlength="100" minlength="1" required="true"/>
	                        	<div style="display:none;"><div class="errorMsg" id="fees_1_error" style="*float:left"></div></div>
	                        </div>
	                    </li>
	                </ul>
	            </div>
	            </div>
	        </div>
	        <div style="margin-bottom:18px"><a id="add_more_qual" onclick="addMore();" href="javascript:void(0);" style="font-size:14px">+ Add more qualification</a></div>
	    <?php else:?>
	    <?php foreach ($mainEducationDetails as $index => $details ):?>
	    <?php $displayText = ($index == 0)?"Current Qualification":"More Qualification";?>
        <div id="qualification_section">
	        <div id="form-section-1" class="form-section">
	        	<label class="qualification-label" id="qualification_text" ><b><?php echo $displayText;?></b>
				<div id="qualificationForm_1">
	                <ul>
      			  <li>
      			      <div class="field-col dummy_autosuggest" style="width: 265px;" >
      			      		<label>Institute Name</label>
      			      		<input type="text" id="keywordSuggest"  class="universal-txt-field" value="<?php echo htmlspecialchars($details["insName"]); ?>" disabled="disabled"/>
					<div id="suggestions_container" style="display:none;"></div>
      			      </div>
			              
                        <div class="field-col"  style="width: 265px;">
                        <label>Location</label>
							<input type="text"  class="universal-txt-field" value="<?php echo (($details['locName'])? $details['locName'].", ":"");?><?php echo $details['cityName'];?><?php echo (($details['stateName'])? ', '.$details['stateName']:"");?>, <?php echo $details['countryName'];?>"  disabled="disabled"/>
                        </div>                    
     				</li>
     				<li>
                        <div class="field-col" style="width: 265px;">
                        <label>Course</label>
							<input type="text"  class="universal-txt-field" value="<?php echo htmlspecialchars($details["courseName"]); ?>" disabled="disabled" />
                        </div>
	                    	
	                    	<div class="field-col" style="padding-top:8px">
	                        	<input type="checkbox" name="" <?php echo ($details["isCurrentlyPursuing"] == "Yes")?'checked':''?> disabled="disabled" /> I am currently pursuing this course
	                        </div>
     				</li>	                    
	                    <li class="mt10">
	                    	<?php $from = substr($details["from"],0,10);
	                    	      $to = substr($details["to"],0,10);
	                    	      $fromArray = explode('-',$from);
	                    	      $toArray = explode('-',$to);
	                    	      $from = $fromArray[2].'/'.$fromArray[1].'/'.$fromArray[0];
	                    	      $to = $toArray[2].'/'.$toArray[1].'/'.$toArray[0];
	                    	?>
	                    	<label class="period-label">Time Period</label>
				 			<div class="flLt" style="margin-right:20px;" id="from_time_1">From &nbsp;
				 			 <input type='text' class="universal-txt-field" value="<?php echo $from;?>" disabled="disabled" style="width:75px; font-size:12px" readonly maxlength='10'   required="true" caption="From Date"  default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fromDate_1'),'fromDate_1_dateImg','dd/MM/yyyy');" />&nbsp;
	                    	</div>
	                    	
				 			<div class="flLt" style="margin-right:20px" id="to_time_1">To &nbsp;
				 			 <input type='text' class="universal-txt-field" value="<?php echo $to;?>" disabled="disabled" style="width:75px; font-size:12px" readonly maxlength='10'  required="true" caption="From Date"  default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('toDate_1'),'toDate_1_dateImg','dd/MM/yyyy');" />&nbsp;
	                    	</div>
	                 	</li>
	                    
	                    <li>
	                    	<div class="field-col" style="width: 265px;" >
	                        	<label>Eligibility</label>
	                        	<input id="ele" type="text" onmouseover="showTipOnline('Please enter academic requirements like Minimum percentage in 12th/Graduation etc',this);" onmouseout="hidetip();" class="universal-txt-field" value="<?php echo $details["eligibilty"]?>" name="" disabled="disabled"/>
	                        </div>
	                        
	                        <div class="field-col" style="width: 265px;">
	                        	<label>Selection process</label>
	                        	<input id="sel" type="text" onmouseover="showTipOnline('Please enter the selection process with name of entrance exams required if any and subsequent process like GD/PI etc',this);" onmouseout="hidetip();" class="universal-txt-field" value="<?php echo $details["selectionProcess"]?>" name="" disabled="disabled" />
	                        </div>
	                    </li>
	                    
	                    <li>
	                    	<div class="field-col" style="width: 265px;">
	                        	<label>Fees</label>
	                        	<input id="fees" type="text" onmouseover="showTipOnline('Please enter the total fees for the course including hostel fees etc',this);" onmouseout="hidetip();" class="universal-txt-field" value="<?php echo $details["fees"]?>" name="" disabled="disabled"/>
	                        </div>
	                    </li>
	                </ul>
	            </div>
	            </div>
	        </div>	 
			<?php endforeach;?>
	    <?php endif;?>    
	    <?php if($edit):?>
        <div id="form-section" class="form-section" style="margin-bottom:0">
        	<label class="qualification-label">
        	<input type="checkbox" name="" <?php echo ($isOfficial == "Yes")?'checked':'';?> <?php echo ($edit)?'disabled':''?>/> I want to be official representative</label>
        	<?php if($isOfficial == "Yes"):?>
			<div id="official_form">
                <ul>
                    <li>

      			      <div class="field-col dummy_autosuggest" id="dummy_autosuggest_official" style="width: 265px;">
      			      <label>Institute Name</label>
      			      		<input type="text"  class="universal-txt-field" value="<?php echo $officailInsName?>" disabled="disabled" onmouseover="showTipOnline('As you type the name of the institute, relevant matches will be shown in drop-down. Please click on the name to select',this);" onmouseout="hidetip();"  id="dummy_input"  onclick="showAutosuggest('official');" />
      			      </div>
      			      
			             <input type="hidden" name="" id="suggested_institutes_official" value="" /> 			  
                        
                        <div class="field-col" style="width: 265px;">
                        <label>Location</label>
							<input class="universal-select" style="width:265px;" id="location_official" value="<?php echo $officailLocName;?>" disabled="disabled" name="" onmouseover="showTipOnline('Please click on your Institute’s location from the Drop-down menu',this);" onmouseout="hidetip();" onchange="loadCourses('official');" />
                        </div>
                    </li>
                    
                    <li>
                    	<div class="field-col" style="width: 265px;">
                    	<label>Course</label>
							<input class="universal-select" style="width:262px;" id="course_official" name="" value="<?php echo $officailCourseName;?>" disabled="disabled" onmouseover="showTipOnline('Please click on your course name from the Drop-down menu',this);" onmouseout="hidetip();" />
                        </div>
                    	
                    	<div class="field-col" style="width: 265px;">
                    	<label>Designation</label>
                        	<input type="text" class="universal-txt-field" name="" value="<?php echo $officialDesignation;?>" disabled="disabled" />
                        </div>
                    </li>
                    
	                    <li class="mt10">
	                    	<?php $from = substr($officialDateFrom,0,10);
	                    	      $to = substr($officialDateTo,0,10);
	                    	      $fromArray = explode('-',$from);
	                    	      $toArray = explode('-',$to);
	                    	      $from = $fromArray[2].'/'.$fromArray[1].'/'.$fromArray[0];
	                    	      $to = $toArray[2].'/'.$toArray[1].'/'.$toArray[0];
	                    	?>	                    
	                    	<label class="period-label">Time Period</label>
				 			<div class="flLt" style="margin-right:20px">From &nbsp;
				 			 <input type='text' class="universal-txt-field" value="<?php echo $from;?>" disabled="disabled" name='' id='fromDateOfficial' style="width:75px; font-size:12px" readonly maxlength='10' required="true" caption="From Date"  default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fromDateOfficial'),'fromDateOfficial_dateImg','dd/MM/yyyy');" />&nbsp;
	                    	</div>
	                    	
				 			<div class="flLt" style="margin-right:20px">From &nbsp;
				 			 <input type='text' class="universal-txt-field" value="<?php echo $to;?>" disabled="disabled" name='' id='toDateOfficial' style="width:75px; font-size:12px" readonly maxlength='10' required="true" caption="From Date"  default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('toDateOfficial'),'toDateOfficial_dateImg','dd/MM/yyyy');" />&nbsp;
	                    	</div>
	                 	</li>
                </ul>
            </div>
            <?php endif;?>
        </div>
        <?php else:?>
        <div id="form-section" class="form-section" style="margin-bottom:0">
        	<p class="qualification-label">
        	<input type="checkbox" id="is_official" name="is_official" onmouseover="showTipOnline('If you are working in an Academic position, please click in the checkbox',this);" onmouseout="hidetip();" onclick="handleOfficialForm();"/> I want to be official representative</p>
			<div id="official_form" style="display:none">
                <ul>
                    <li>

      			      <div class="field-col dummy_autosuggest" id="dummy_autosuggest_official">
      			      		<input type="text"  class="universal-txt-field" value="Type Institute name..." onmouseover="showTipOnline('As you type the name of the institute, relevant matches will be shown in drop-down. Please click on the name to select',this);" onmouseout="hidetip();"  id="dummy_input"  onclick="showAutosuggest('official');" />
      			      		<div style="display:none;"><div class="errorMsg" id="institute_official_error" style="*float:left"></div></div>
      			      </div>
      			      
			             <input type="hidden"  name="suggested_institutes_official" onmouseover="showTipOnline('As you type the name of the institute, relevant matches will be shown in drop-down. Please click on the name to select',this);" onmouseout="hidetip();" id="suggested_institutes_official" value="" /> 			  
                        
                        <div class="field-col">
							<select class="universal-select" id="location_official" name="location_official" onmouseover="showTipOnline('Please click on your Institute’s location from the Drop-down menu',this);" onmouseout="hidetip();" onchange="loadCourses('official');" validate="validateSelect" caption="Location"><option value="">Location </option></select>
							<div style="display:none;"><div class="errorMsg" id="location_official_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    
                    <li>
                    	<div class="field-col">
							<select class="universal-select" id="course_official" name="course_official" onmouseover="showTipOnline('Please click on your course name from the Drop-down menu',this);" onmouseout="hidetip();"  validate="validateSelect" caption="Course"><option value="">Course</option></select>
							<div style="display:none;"><div class="errorMsg" id="course_official_error" style="*float:left"></div></div>
                        </div>
                    	
                    	<div class="field-col">
                        	<input type="text" id="designation" class="universal-txt-field" validate="validateStr" name="designation" default="Designation" value="Designation" onfocus='checkTextElementOnTransition(this,"focus");' blurMethod='checkTextElementOnTransition(this,"blur");' minlength="1" maxlength="150" caption="Designation" />
                        	<div style="display:none;"><div class="errorMsg" id="designation_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    
	                    <li class="mt10">
	                    	<label class="period-label">Time Period</label>
				 			<div class="flLt" style="margin-right:20px">From &nbsp;
				 			 <input type='text' class="universal-txt-field" name='fromDateOfficial' id='fromDateOfficial' style="width:75px; font-size:12px" readonly maxlength='10' caption="From Date" validate="validateCaDate"  default = 'dd/mm/yyyy' value="dd/mm/yyyy" onfocus='checkTextElementOnTransition(this,"focus");' blurMethod='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fromDateOfficial'),'fromDateOfficial_dateImg','dd/MM/yyyy');" />&nbsp;
				 			 <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='fromDateOfficial_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fromDateOfficial'),'fromDateOfficial_dateImg','dd/MM/yyyy'); return false;" />
					  		<script>
								document.getElementById("fromDateOfficial").style.color = "#ADA6AD";
						    </script>
							<div style="display:none;"><div class="errorMsg" id="fromDateOfficial_error" style="*float:left"></div></div>						    
	                    	</div>
	                    	
				 			<div class="flLt" style="margin-right:20px">To &nbsp;
				 			 <input type='text' class="universal-txt-field" name='toDateOfficial' id='toDateOfficial' style="width:75px; font-size:12px" readonly maxlength='10' caption="From Date" validate="validateCaDate" default = 'dd/mm/yyyy' value="dd/mm/yyyy" onfocus='checkTextElementOnTransition(this,"focus");' blurMethod='checkTextElementOnTransition(this,"blur");'  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('toDateOfficial'),'toDateOfficial_dateImg','dd/MM/yyyy');" />&nbsp;
				 			 <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='toDateOfficial_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('toDateOfficial'),'toDateOfficial_dateImg','dd/MM/yyyy'); return false;" />
					  		<script>
								document.getElementById("toDateOfficial").style.color = "#ADA6AD";
						    </script>
						    <div style="display:none;"><div class="errorMsg" id="toDateOfficial_error" style="*float:left"></div></div>
	                    	</div>
	                    	 <div style="display:none;"><div class="errorMsg" id="official_date_error" style="*float:left"></div></div>
	                 	</li>
                </ul>
            </div>
        </div>        
        <?php endif;?>
         <div id="form-section" class="form-section">
        	<label class="qualification-label">About Me</label>
			<div>
                <ul>
                    <li>
                    	<div class="field-col" style="width:565px">
                        	<textarea style="width:98%;color: #ABABAD;" rows="3" cols="3" id="about_me" name="about_me" class="universal-select" default="Please enter other details like certifications, entrances appeared for etc." onfocus="checkTextElementOnTransition(this,'focus');" blurMethod="checkTextElementOnTransition(this,'blur');" maxlength="1000" minlength="10" validate="validateStr" caption="About me details" required="true"><?php if(isset($aboutMe) && $aboutMe!=''){ echo $aboutMe;}else{?>Please enter other details like certifications, entrances appeared for etc.<?php } ?></textarea>
							<div style="display:none;"><div class="errorMsg" id="about_me_error" style="*float:left"></div></div>
                        </div>
                    </li>
                </ul>
             </div>
        </div>
        
        <div id="form-section" class="form-section">
        	<h5>Social Details</h5>
        	<div class="" style="margin-top:5px;display:none float:left;width:100%;margin-bottom:10px;" id="err_social">
				<div class="errorMsg" id = "quickerror_social_ForCA"></div>
			</div>
            <div>
                <ul>
                    <li>
                        <div class="field-col">
                        	<label>Your Facebook Profile</label>
                        	<div class="social-fileds">
								<input type="text" value="<?php echo $facebookURL;?>" name='facebookURL' id='facebookURL' validate="validateSocial" minlength="3" maxlength="200" caption="Facebook URL" />                        	
                                <span class="fb-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="facebookURL_error" style="*margin-left:3px;"></div></div>                            
                        </div>
                        
                        <div class="field-col">
                        	<label>Your Linkedin Profile</label>
                        	<div class="social-fileds">
                        		<input type="text" value="<?php echo $linkedInURL;?>" name='linkedInURL' id='linkedInURL' validate="validateSocial" minlength="3" maxlength="200" caption="LinkedIn URL" />
                                <span class="in-icn"></span>
                            </div>
                            <div style="display:none;float:left;width:100%;"><div class="errorMsg" id="linkedInURL_error" style="*margin-left:3px;"></div></div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="field-col">
                        	<label>Your Youtube Channel</label>
                        	<div class="social-fileds">
								<input type="text" value="<?php echo $youtubeURL;?>" name='youtubeURL' id='youtubeURL' validate="validateSocial" minlength="3" maxlength="200" caption="Youtube URL" />                        	
                                <span class="utube-icn"></span>
                            </div>
                            <div style="display:none;float:left;width:100%;"><div class="errorMsg" id="youtubeURL_error" style="*margin-left:3px;"></div></div>
                        </div>
                        
                        <div class="field-col">
                        	<label>Your Blog URL</label>
                            <div class="social-fileds">
                                <input type="text" value="<?php echo $blogURL;?>" name='blogURL' id='blogURL' validate="validateSocial" minlength="3" maxlength="200" caption="Blog URL" />
                                <span class="blog-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="blogURL_error" style="*margin-left:3px;"></div></div>                            
                        </div>
                    </li>
                    
                    <li>
                        <div class="field-col">
                        	<label>Your Twitter Profile</label>
                            <div class="social-fileds">
								<input type="text" value="<?php echo $twitterURL;?>" name='twitterURL' id='twitterURL' validate="validateSocial" minlength="3" maxlength="200" caption="Twitter URL" />                            
                                <span class="twitt-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="twitterURL_error" style="*margin-left:3px;"></div></div>                            
                        </div>
                    </li>
                    <?php if($userId<=0 || $userId==''){ ?>
                    <li class="captcha-row">
                    	<p>Type in the character you see in the picture below</p>
                        <img style="vertical-align:middle" src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeForCAReg" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "registerCaptacha_ForCA" alt="" /> &nbsp; 
                        <input type="text" class="universal-txt-field" id = "securityCode_ForCA" name = "securityCode_ForCA" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:140px; vertical-align:middle; padding:7px" />
                        <div class="spacer10 clearFix"></div>
                        <input type="checkbox" checked id="quickagree_ForCA" name="agree" required="true" /> I agree to the <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
                        <div style="padding-left:19px;"><div class="errorMsg" id="securityCode_ForCA_error"></div></div>
                        <div class="errorPlace" style="margin-top:2px;float:left;width:100%;" >
							<div class="errorMsg" id = "quickagree_ForCA_error"></div>
						</div>
						
						<div style="display:none;float:left;width:100%;">
							<div class="errorMsg" id = "quickerror_ForCA"></div>
						</div>	
                        
                    </li>
		    <?php } ?>
                    <li> 
                    	<input type="button" value="Submit" id="CASubmitButton" class="orange-button" onclick="startValidating();removeHelpText($('CAProfileForm')); if(validateFields($('CAProfileForm')) != true){ validateCAForm($('CAProfileForm')); validationFail(); return false;} if( validateCAForm($('CAProfileForm')) != true){validationFail(); return false;}  storeCAData($('CAProfileForm')); return false;"/>
                    	&nbsp;<span id="waitingDiv" style="display:none"><img src='/public/images/working.gif' border=0 align=""></span>
                    </li>
                    
                </ul>
            </div>
        </div>
        
        <div class="clearFix"></div>
        </form>	
    </div>
    
    <div id="right-col">
    	<h3>Play the Lead Role as 'Campus Ambassador'</h3>
        <div id="rt-content">
            <ul>
                <li>
                    <h2>Be the Face of your Institute</h2>
                    <p>Any important happenings at your institute – Reach out and inform students who would love to <br/><a href="javascript:void(0);" onmouseover="showCloudDiv('answerQuestionCloud');" onmouseout="hideCloudDiv('answerQuestionCloud');">Know More</a></p>
                    <div class="face-inst"></div>
                     <div class="info-cloud" style="display:none; top:37px; width:378px; left:-380px;" id="answerQuestionCloud">
                        <div class="info-inner">
                            <div class="figure-cont"><div class="face-inst"></div></div>
                            <div class="details">
                            	<strong>Answer Questions</strong>
                                <p>Any important happenings at your institute – Reach out and inform students who would love to.</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
                
                <li>
                    <h2>Get Awarded & Rewarded</h2>
                    <p>Earn certificates and win exciting prizes – And of course, the blessings and admiration of millions of aspirants <br />
<a href="javascript:void(0);" onmouseover="showCloudDiv('answerQuestionCloud_0');" onmouseout="hideCloudDiv('answerQuestionCloud_0');">Know More</a> </p>
                    <div class="award-reward"></div>
                    <div class="info-cloud" style="display:none; top:60px; width:378px; left:-366px; top:44px\9;" id="answerQuestionCloud_0">
                        <div class="info-inner">
                            <div class="figure-cont"><div class="award-reward"></div></div>
                            <div class="details">
                            	<strong>Get Awarded & Rewarded</strong>
                                <p>Earn certificates and win exciting prizes – And of course, the blessings and admiration of millions of aspirants</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
                
                <li>
                    <h2>Be a Mentor & Guide</h2>
                    <p>Show the way – Your answers can help someone make a better and well informed career decision <br/><a href="javascript:void(0);" onmouseover="showCloudDiv('answerQuestionCloud_1');" onmouseout="hideCloudDiv('answerQuestionCloud_1');">Know More</a></p>
                    <div class="mentor-guide"></div>
                    <div class="info-cloud" style="display:none; top:44px; left:-476px; width:482px;" id="answerQuestionCloud_1">
                        <div class="info-inner" style="width:430px">
                            <div class="figure-cont"><div class="mentor-guide"></div></div>
                            <div class="details">
                            	<strong>Be a Mentor & Guide</strong>
                                <p>Show the way – Your answers can help someone make a better and well informed career decision.</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
                
                
            </ul>
        </div>
    </div>
    
    <div class="clearFix"></div>
</div>

<?php 
	$this->load->view('common/footer');
?>
<script>
	try{
	//	addOnFocusToopTipOnline(document.getElementById('CAProfileForm'));
		//addOnFocusToopTipOnline(document.getElementById('qualificationForm_1'));
	    addOnBlurValidate(document.getElementById('CAProfileForm'));
	} catch (ex) {
	}    
</script>
