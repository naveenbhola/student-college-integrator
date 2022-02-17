<?php		$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
		if($eventId != '' && is_numeric($eventId)) {
			$caption = 'Update Event';
			$buttonCaption = 'Update Event';
			$cancelUrl = '/events/Events/eventDetail/1/'.$eventId;	
		} else {
		 	$caption = 'Add an Event';
			$buttonCaption = 'Add Event';
			$cancelUrl = '/events/Events/index';
		}
		$buttonCaption = 'Submit';
		$headerComponents = array(
								'css'	=>	array(
											'events',
											'raised_all',
											'mainStyle',
											'cal_style',
											'header'
										),
								'js'	=>	array(
											'common',
											'user',
											'events',
											'cityList',
											'tooltip',
											'prototype',
											'CalendarPopup'
										),
								'title'	=>	$caption. ' ? Education Events',
								'tabName'	=>	'Shiksha.com - Education Events - Admissions - Scholarships - Results - Career Fairs - India & Abroad',
								'taburl' =>  '/index.php/events/Events/index',
								'metaDescription' => 'Add Education Events, Admissions, Scholarships, Examination Results, Career Fairs in India and Abroad along with result announcement and exam notification dates.',
								'metaKeywords'	=>'Add an Event, Education Events, Admissions, Scholarships, Examination Results, Career Fairs in India, events abroad, exam notifications, result announcements, general events, education , event details, contact information, shiksha',
								'product' => 'events',
								'search'=> false,
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'callShiksha'=>1
							);
		$this->load->view('common/header', $headerComponents);
		//$this->load->view('common/homepage', $headerComponents);
		$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
                $this->load->view('events/headerSearchPanelForEvents',$dataForHeaderSearchPanel);
                $this->load->view('common/calendardiv');
    $listingName = $listingName != '' ? $listingName .' - ' : '';		
		
	$dateArr = isset($start_date) ? explode(' ',$start_date) : '';
	if(is_array($dateArr)) {
		$start_date = $dateArr[0];
	}
	$endDateArr = isset($end_date) ? explode(' ',$end_date) : '';
	if(is_array($endDateArr)) {
		$end_date = $endDateArr[0];
	}
	
	$event_title = isset($event_title) ? $event_title : '';
	$start_date = isset($start_date) ? $start_date : '';
	$Address_Line1 = isset($Address_Line1) ? $Address_Line1 : '';
	$end_date = isset($end_date) ?  $end_date : '';
	$venue_id = isset($venue_id) ? $venue_id : '';
	$description = isset($description) ? $description : '';
	$city = isset($city) ? $city : '';
	$state = isset($state) ? $state : '';
	$country = isset($country) ? $country : '';
	if($country && $country > 2){
		$category_tree = $category_tree_abroad;
	}
	$zip = isset($zip) ? $zip : '';
	$fax = isset($fax) ? $fax : '';
	$mobile = isset($mobile) ? $mobile : '';
	$phone = isset($phone) ? $phone : '';
	$email = isset($email) ? $email : '';
	$venue_name = isset($venue_name) ? $venue_name : '';
	$venue_id = isset($venue_id) ? $venue_id : '';
	$event_url = isset($event_url) ? $event_url : '';
	$contact_person = isset($contact_person) ? $contact_person : '';
	$fromOthers = isset($fromOthers) ? $fromOthers : '0';
	$selectedCategoryId = isset($categoryCsv) ? $categoryCsv : '';
	if(($end_date == $start_date) || $end_date == '0000-00-00') {
		$end_date = '';
	}
	$startTimeArray = explode(":",$start_time);
	if(is_array($startTimeArray)) {
		$mins = isset($startTimeArray[1]) ? $startTimeArray[1] : '';
		$hour = ($startTimeArray[0] % 12);
		$hour = ($hour == 0 && $mins == '00') ? 12 : $hour ;
		$hour = $hour < 10 ? '0'. $hour : $hour;
		$start_time = $hour .':00:00';
		$startTimeStampAM = '';
		$startTimeStampPM = '';
		if(($startTimeArray[0] > 12 || $startTimeArray[0] == 0 ) && $eventId != '') {
			$startTimeStampPM = 'checked';
		} else {
			$startTimeStampAM = 'checked';
		}
	} else {
		$startTimeStampAM = 'checked';
		$startTimeStampPM = '';
	}
	$endTimeArray = explode(":",$end_time);
	if(is_array($endTimeArray)) {
		$mins = isset($endTimeArray[1]) ? $endTimeArray[1] : '';
		$hour = ($endTimeArray[0] % 12);
		$hour = ($hour == 0 && $mins == '00' && $end_date !='') ? 12 : $hour ;
		$hour = $hour < 10 ? '0'. $hour : $hour;
		$end_time = $hour .':00:00';
		$endTimeStampAM = '';
		$endTimeStampPM = '';
		if(($endTimeArray[0] > 12 || $endTimeArray[0] == 0) && $eventId != '' && $mins != '01' && $end_date != '') {
			$endTimeStampPM = 'checked';
		} else {
			$endTimeStampAM = 'checked';
		}
	} else {
		$endTimeStampAM = 'checked';
		$endTimeStampPM = '';
	}
?>
<style>

input{
	font-family:Arial,Helvetica,sans-serif;
	font-size:11px;
}
</style>
<div class="mar_full_10p">
<?php 
//	$this->load->view('events/leftPanelEventDetail');
?>

	<div>
		<div class="normal1p_blk fontSize_16p OrgangeFont">
			<h1><span class="normal1p_blk fontSize_16p OrgangeFont"><strong><?php echo $caption; ?></strong></span></h1>
		</div>
		<form id="eventForm" method="post" action="/events/Events/eventAdd" onSubmit="return validateEvent(this);" novalidate="novalidate">
		<div class="lineSpace_10">&nbsp;</div>
		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
				<div class="lineSpace_5">&nbsp;</div>
				<div class="lineSpace_10">&nbsp;</div>
				
				<div align="center" style="color:#666666;width:100%">All fields marked with <span class="redcolor">*</span> are Required</div>
				<div class="mar_full_10p normal1p_blk fontSize_12p OrgangeFont">
					<div class=="wdh100"><b>Event Details</b></div>
				</div>
				<div class="grayLine wdh100">&nbsp;</div>
				
            
				<input type="hidden" name="event_url" id="event_url" value="1"/>
				<input type="hidden" name="event_id" id="event_id" value="<?php echo $eventId; ?>"/>
				<input type="hidden" name="listingId" id="listingId" value="<?php echo $listingId; ?>"/>
				<input type="hidden" name="listingType" id="listingType" value="<?php echo $listingType; ?>"/>
				<input type="hidden" name="venue_id[]" id="venue_id" value="<?php echo $venue_id; ?>"/>
				<input type="hidden" name="Tag" id="Tag" value=""/>
				<div class="lineSpace_12">&nbsp;</div>
			    <div class="clear_L"></div>
				<div class="lineSpace_13">&nbsp;</div>
                                <div align="center">
                                	<div class="row">
                                    		<div class="r1 bld">Event Type : <span class="redcolor">*</span></div>
				    		<div class="r2">
				    			<select required="true" name="fromOthers" id="fromOthers" onChange="checkExamBox(this.value);" class="countryCombo textboxEventW31 normal1p_blk_verdana fontSize_16p">
						    <option value="0" <?php echo $fromOthers == "0" ? 'selected' :'';?>>Application Submission Deadline</option>
						    <option value="1" <?php echo $fromOthers == "1" ? 'selected' :'';?>>Course Commencement</option>
						    <option value="2" <?php echo $fromOthers == "2" ? 'selected' :'';?>>Result Declaration</option>
						    <option value="3" <?php echo $fromOthers == "3" ? 'selected' :'';?>>Examination Date</option>
						    <option value="4" <?php echo $fromOthers == "4" ? 'selected' :'';?>>Form Issuance</option>
						    <option value="5" <?php echo $fromOthers == "5" ? 'selected' : ''; ?>>General</option>
							</select>
						    </div>
					    <div class="clear_L"></div>
                                 	</div>
                                        <div class="row errorPlace">
                                                <div class="lineSpace_5">&nbsp;</div>
                                                <div class="r1">&nbsp;</div>
                                                <div id="eventType_error" class="errorMsg r2">&nbsp;</div>
                                                <div class="clear_L"></div>
                                        </div>
                                    </div>
					<div class="lineSpace_13">&nbsp;</div>
					<div class="row">
			            <div class="r1 bld">Event Start Date : <span class="redcolor">*</span>&nbsp;</div>
			            <div class="inline-l">
			            	<input style="width:75px;" type="text" required="true" validate="validateStartDate" name="start_date" id="start_date" value="<?php echo $start_date; ?>" readonly maxlength="10" size="15" class="" onchange="checkStartDateForEvent(document.getElementById('start_date'));" onClick="cal.select($('start_date'),'sd','yyyy-MM-dd');" caption="Start Date"/>
                   			<img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('start_date'),'sd','yyyy-MM-dd');" />
						</div>
				 
			            <div class="clear_L"></div>
					</div>
                    
                    
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="start_date_error" class="errorMsg r2" ></div>
						<div class="clear_L"></div>
					</div>
					
					<div class="lineSpace_13">&nbsp;</div>
					<?php 
					if($fromOthers<4){
					$end_date='';
					echo $end_date;
					}?>
					 <div id="endDateSelectPanel" style="display:none;">
					<div class="row">
			            <div class="r1 bld">Event End Date : &nbsp;</div>
			            <div class="inline-l">
			            	<input style="width:75px;" type="text" name="end_date" id="end_date" value="<?php echo $end_date; ?>" validate="validateEndDate" maxlength="10" size="15" readonly class="" onClick="cal.select($('end_date'),'ed','yyyy-MM-dd');"  caption="End Date"/>
                   			<img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="cal.select($('end_date'),'ed','yyyy-MM-dd');" />
			            </div>
					
			            <div class="clear_L"></div>
					</div>
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="end_date_error" class="errorMsg r2"  ></div>
						<div class="clear_L"></div>
					</div>
					</div>
					<div class="lineSpace_13">&nbsp;</div>
					<?php $eventTitle = $listingName.$event_title;
						  $eventTitle = (trim($eventTitle) != "")?htmlentities($eventTitle):""; 	
					?>
					 <div class="row">
			            <div class="r1 bld">Event Title : <span class="redcolor">*</span>&nbsp;</div>
			            <div class="r2">
			            	<input type="text" name="event_title" id="event_title" validate="validateStr" profanity="true" required="true" value="<?php echo $eventTitle; ?>" maxlength="250" minlength="10" class="w62_per" tip="event_name" caption="Event Name"/>
			            </div>
			            <div class="clear_L"></div>
					</div>
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="event_title_error" class="errorMsg r2"></div>
						<div class="clear_L"></div>
					</div> 
					<div class="lineSpace_13">&nbsp;</div>					
				
						
					<div class="row">
			            <div class="r1 bld">Country : <span class="redcolor">*</span></div>
			            <div class="r2">
			            	<select required="true" name="country[]" id="country" class="countryCombo textboxEventW31 normal1p_blk_verdana fontSize_16p" onchange="getCitiesForCountryForEvent();">
		            			<?php
	                                foreach($country_list as $countryItem) {
	                                    $countryId = $countryItem['countryID'];
	                                    $countryName = $countryItem['countryName'];
	                                    if($countryId == 1) { continue; }
	                                    if($countryId == $country) { $selected = 'selected'; }
	                                    else { $selected = ''; }
	                            ?>
									<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
	                            <?php
	                                }
	                            ?>
	                        </select>
			            </div>
			            <div class="clear_L"></div>
					</div>
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="country_error" class="errorMsg r2">&nbsp;</div>
						<div class="clear_L"></div>
					</div>
					<div class="lineSpace_13">&nbsp;</div>
					<div class="row">
			            <div class="r1 bld">City : <span class="redcolor">*</span></div>
			            <div class="r2">
			           	<span> 
				           <select name="cities[]" id="cities" onChange="checkCity(this, 'updateInstitutes');" validate="validateStr" required=true  minlength="1" caption="City" maxlength="5"/>
							</select>
							<?php if($city==1){ ?>
							<input id="allCities" type="checkbox" onClick="allCitiesChecked();" name="All cities" value="1" style="width:15px" checked/> All Cities
							<?php }else{?>
							<input id="allCities" type="checkbox" onClick="allCitiesChecked();" name="All cities" style="width:15px" value="1"/> All Cities
							<?php } ?>
							<input type="text" validate="validateStr" maxlength="25" required=true minlength="2" name="cities_other[]" id="cities_other" value="" style="display:none" caption="City Name"/>
						</span>	
			            </div>
			            <div class="clear_L"></div>
					</div>
					<div id="showError" style="display:none">
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="cities_error" class="errorMsg r2"></div>
						<div class="clear_L"></div>
					</div>
					</div>
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="cities_other_error" class="errorMsg r2"></div>
						<div class="clear_L"></div>
					</div>
					<div class="lineSpace_13">&nbsp;</div>
						<div class="row">
			            <div class="r1 bld">Event Category : <span class="redcolor">*</span>&nbsp;</div>
			            <div class="r2" id="c_categories_combo">
			            </div>
			            <div class="clear_L"></div>
					</div>
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="selectCategory_error" class="errorMsg r2"></div>
						<div class="clear_L"></div>
					</div>
					<div class="lineSpace_13">&nbsp;</div>	
					<div id="venueSelectPanel" style="display:none;">
					 <div class="row">
                                    <div class="r1 bld">Event Venue:&nbsp;</div>
                                    <div class="r2">
                                        <input type="text" name="Address_Line1[]" id="Address_Line1" value="<?php echo htmlentities($Address_Line1); ?>"  validate="validateStr" maxlength="100" minlength="10" class="w62_per" caption="Event Venue" tip="event_venue" />
                                    </div>
                                    <div class="clear_L"></div>
                                        </div>
                                        <div class="row errorPlace">
                                                <div class="lineSpace_5">&nbsp;</div>
                                                <div class="r1">&nbsp;</div>
                                                <div id="Address_Line1_error" class="errorMsg r2"></div>
                                                <div class="clear_L"></div>
                               </div>
                                        <div class="lineSpace_13">&nbsp;</div>
					 <div class="row">
                                    <div class="r1 bld">Event about Country: <span class="redcolor">*</span></div>
                                    <div class="r2">
                                        <select required="true" name="countryAssoc[]" id="countryAssoc" tip="event_about_country" class="countryCombo textboxEventW31 normal1p_blk_verdana fontSize_16p">
                                                <?php
                                        foreach($country_list as $countryItem) {
                                            $countryId = $countryItem['countryID'];
                                            $countryName = $countryItem['countryName'];
                                            if($countryId == 1) { continue; }
                                            if($countryId == $assoc_country) { $selected = 'selected'; }
                                            else { $selected = ''; }
                                    ?>
                                                                        <option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                    </div>
                                    <div class="clear_L"></div>
                                        </div>
                                        <div class="row errorPlace">
                                                <div class="lineSpace_5">&nbsp;</div>
                                                <div class="r1">&nbsp;</div>
                                                <div id="country_error" class="errorMsg r2">&nbsp;</div>
                                                <div class="clear_L"></div>
                                        </div>
			
				<input type="hidden" name="board_id" id="board_id" value="1"/>
				</div>
				<div class="lineSpace_13">&nbsp;</div>	
				<div class="row">
			            <div class="r1 bld">Additional Details : &nbsp;</div>
			            <div class="r2 inline">
			            	<textarea style="height: 100px;" title="spellcheck" name="description" id="description" maxlength="2000" minlength="10" class="w62_per" tip="event_details" caption="Event Details" validate="validateStr"><?php echo htmlentities($description); ?></textarea>
			            </div>
			            <div class="clear_L"></div>
					</div>
					<div class="row errorPlace">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="r1">&nbsp;</div>
						<div id="description_error" class="errorMsg r2"></div>
						<div class="clear_L"></div>
					</div>
				
				<div class="lineSpace_10">&nbsp;</div>
				<div class="lineSpace_10">&nbsp;</div>
				
					
				<div class="grayLine">&nbsp;</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div align="center">
<input type="Submit" id="submitButton" value="Submit" class="submitGlobal"/> &nbsp; <input type="button" value="Cancel" class="cancelGlobal" onclick="location.replace('<?php echo $cancelUrl; ?>');" style="position:relative;top:0px; *top:0" />
				</div>
			
				<div class="lineSpace_10">&nbsp;</div>
				<div class="lineSpace_5">&nbsp;</div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
        </form>
	</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<?php 
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
<script>
	var completeCategoryTree = eval(<?php echo $category_tree; ?>);
	var categoryTreeIndia = eval(<?php echo $category_tree_india; ?>);
	var categoryTreeAbroad = eval(<?php echo $category_tree_abroad; ?>);
	if(document.getElementById('c_categories_combo'))
	{
		getCategories(false,'c_categories_combo','selectCategory','selectCategory',false);
	}	
	fillProfaneWordsBag() ;
	var completeCategoryTree = eval(<?php echo $category_tree; ?>);
	addOnFocusToopTip(document.getElementById('eventForm'));
	var selectBoxes = document.getElementsByTagName('select');
	for(var selectBoxCount = 0; selectBoxCount < selectBoxes.length; selectBoxCount++) {
		selectBoxes[selectBoxCount].className = '';
	}
	var cal = new CalendarPopup("calendardiv");
	addOnBlurValidate(document.getElementById('eventForm'));
	getCitiesForCountryForEvent(<?php echo $city ?>);
	var valueToSelect = '<?php echo $selectedCategoryId;?>';
	valueToSelect = valueToSelect.split(',');
	selectMultiComboBox(document.getElementById('selectCategory'),valueToSelect);					
    function checkExamBox(val) {
	if(val>3){
	document.getElementById('endDateSelectPanel').style.display = 'block';
        document.getElementById('venueSelectPanel').style.display = 'block'; 
        }else{
	document.getElementById('Address_Line1').value='';
	document.getElementById('end_date').value='';
	//document.getElementById('description').value='';
	document.getElementById('endDateSelectPanel').style.display = 'none';
        document.getElementById('venueSelectPanel').style.display = 'none'; 
        }
   	}
    checkExamBox(<?php echo (is_numeric($fromOthers) ? $fromOthers : 0) ; ?>);
    function allCitiesChecked(){
	var value=document.getElementById('cities').value;
        if(document.getElementById('allCities').checked){
        document.getElementById('showError').style.display = 'none';
	document.getElementById('cities').disabled=true;
        }else{
        document.getElementById('showError').style.display = 'block';
	document.getElementById('cities').disabled=false;
        }
        }	
	allCitiesChecked();
</script>
