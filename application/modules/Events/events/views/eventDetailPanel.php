<?php
	
	$isCmsUser = false;
	if(is_array($validateuser) && $validateuser != "false") {
		if($validateuser[0]['usergroup'] == 'cms' || $user_id == $validateuser[0]['userid']) {
			$isCmsUser = true;
		}
	}
	$dateArr = isset($start_date) ? explode(' ',$start_date) : '';
	$start_date = $dateArr[0];
	$start_time = $dateArr[1];
	$endDateArr = isset($end_date) ? explode(' ',$end_date) : '';
	$end_date = $endDateArr[0];
	$end_time = $endDateArr[1];
	
	$abuse = isset($abuse) ? $abuse : 0;
	$fromOthers = isset($fromOthers) ? $fromOthers : '0';
	$event_title = isset($event_title) ? $event_title : 'No Such Event Available.';
	$start_date = isset($start_date) ? getDateFormat($start_date) : ' ';
	$start_time = isset($start_time) ? getTimeFormat($start_time) : ' ';
	$Address_Line1 = isset($Address_Line1) ? $Address_Line1 : '';
	$end_date = isset($end_date) ?  getDateFormat($end_date) : '';
	$end_time = isset($end_time) ?  getTimeFormat($end_time) : '';
	$description = isset($description) ? nl2br_Shiksha($description) : '';
	$city = isset($cityName) ? $cityName : '';
	$state = isset($state) ? $state : '';
	$country = isset($countryName) ? $countryName : '';
	$zip = isset($zip) ? $zip : '';
	$fax = isset($fax) ? $fax : '';
	$mobile = isset($mobile) ? $mobile : '';
	$phone = isset($phone) ? $phone : '';
	$email = isset($email) ? $email : '';
	$venue_name = isset($venue_name) ? $venue_name : '';
	$venue_id = isset($venue_id) ? $venue_id : '';
	$event_url = isset($event_url) ? prep_url(strtolower($event_url)) : '';
	$contact_person = isset($contact_person) ? $contact_person : '';
	$categoryId = isset($categoryId) ? $categoryId : '';
	$subCategoryId = isset($subCategoryId) ? $subCategoryId : '';
	$categoryName = isset($categoryName) ? $categoryName : '';
	$subCategoryName = isset($subCategoryName)?$subCategoryName : '';
	$refererAddEvent = isset($refererAddEvent)?$refererAddEvent : '';
	$reportedAbuse = isset($reportedAbuse)?$reportedAbuse: 0;
	$category =  $categoryName;
	//echo $zip ."zip";
	if($subCategoryName != '' && $categoryName != '') {
		$category .= ' > ';
	}
	$category .= $subCategoryName;
	if($venue_name == '' && $event_url == '' && $contact_person == '' && $email == '' && $fax == '' && $mobile == '' && $phone == '') {
		$showEventOrganizerSection = false;
	} else {
		$showEventOrganizerSection = true;
	}
	if($end_date == $start_date && $end_time =='') {
		$end_date = '';
	}
	if($end_date !== '') {
		$dateDelim = "to";
	} else {
		$dateDelim = "";
	}
	if($end_time !== '') {
		$timeDelim = "to";
	} else {
		$timeDelim = "";
	}
	$location = "";
	if($Address_Line1 != "") {
		$location .= $Address_Line1;
	}
	if($city != "") {
		if($location != "") {
			$location .= ", ";
		}
		$location .= $city;
	}
	if($state != "") {
		if($location != "") {
			$location .= ", ";
		}
		$location .= $state;
	}
	if($country != "") {
		if($location != "") {
			$location .= ", ";
		}
		$location .= $country;
	}
	if($zip != "" && $zip != 0) {
		if($location != "") {
			$location .= " - ";
		}
		$location .= $zip;
	}
	$eventTypeArray = array('0'=>'Event','1'=>'Admission Notification', '2'=>'Result', '3'=>'Exam Notification');
	$eventType = $eventTypeArray[$fromOthers];
	function getDateFormat($dateStr){
		$dateArray = explode("-",$dateStr);
		$year = $dateArray[0];
		$month = $dateArray[1];
		$day = $dateArray[2];
		return date("l, F d, Y", mktime(0, 0, 0, $month, $day, $year));
	}		
		
	function getTimeFormat($timeStr){
		$dateArray = explode(":",$timeStr);
		$hours = $dateArray[0];
		$mins = $dateArray[1];
		$secs = $dateArray[2];
		$hours %= 24;
		if($hours>12) {
			$hours %= 12;
			$suffix = ' pm';
		} elseif($hours==0){
			$hours = 12;
			$suffix = ' pm';
		}else {
			$suffix = ' am';
		}
		if($mins != '00' ) {
			return '';
		} else {
			return $hours.":".$mins .$suffix;
		}
	}	

?>
<div class="" style="width:100%; float:left">
<?php
			if($refererAddEvent != ''){
				if(strtolower($refererAddEvent) == 'update'){
					$showMessage = 'Event Successfully Updated.';
				} elseif(strtolower($refererAddEvent) == 'success') {
					$showMessage = 'Event Successfully Created.';				
				} else {
					$showMessage = '';
				}
		?>
<div class="fontSize_11p" align="center"> <i><?php echo $showMessage; ?></i> </div>
<?php
			}
		?>
<div> <span class="blogheading"><a href="/events/Events/index">Important Deadlines</a> &gt; <?php echo $event_title; ?></span> </div>
<div class="lineSpace_11">&nbsp;</div>
<div class="raised_greenGradient_ww">
<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<div class="boxcontent_greenGradient_ww">
  <?php if($end_date==''){
				$endStyle = 'width:39px;overflow:hidden';
				$titleMargin = 'margin-left:49px';
			} else {
				$endStyle = 'width:97px';
                                $titleMargin = 'margin-left:107px';
			}
			?>
  <div class="bgEnt wdh100">
    <div class="mar_full_10p dtPb7">
      <div class="dtPt5">
        <div class="float_R">
	<?php
                                                        if(!(is_array($validateuser) && $validateuser != "false")) {
                                                        $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $event_id; ?>/event/<?php echo $event_id; ?>/<?php echo $event_title; ?>");
                                                        $onClick = "calloverlayEvents('$event_id','".addslashes($event_title)."','EVENTS');return false;";
                                                        }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                        echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = "calloverlayEvents('$event_id','".addslashes($event_title)."','EVENTS');return false;";
                                                        }
                                                        }
                                                        ?>
          <div><input type="button" value="Subscribe to The Event" onClick="<?php echo $onClick?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo addslashes($event_title); ?>');return false;" class="dt_btnScrib" /></div>
        </div>
        <div style="margin-right:166px">
          <div class="float_L wdh100">
            <div class="float_L dtEnt" style="<?php echo $endStyle; ?>">
              <div class="dtbox">
                <div class="whiteColor"><?php echo date("M",strtotime($start_date));?></div>
                <div><?php echo date("j",strtotime($start_date));?></div>
              </div>
              <?php if($end_date!=''){ ?>
              <div class="dtbox dtml19">
                <div class="whiteColor"><?php echo date("M",strtotime($end_date));?></div>
                <div><?php echo date("j",strtotime($end_date));?></div>
              </div>
              <?php } ?>
            </div>
            <div style="<?php echo $titleMargin; ?>">
             <h1><div class="Fnt16 orangeColor"><b><?php echo $event_title; ?></b></div>
              <div class="normaltxt_11p_blk fontSize_12p"><?php echo $city; ?> , <?php echo $country ?></div></h1>
            </div>
          </div>
          <div class="clear_B">&nbsp;</div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear_B">&nbsp;</div>
  <div class="mar_full_10p">
  	<div class="spacer10 clearFix"></div>
    <p class="wdh100"> <b>Date:</b><br />
      <span class="normaltxt_11p_blk fontSize_12p"><?php echo $start_date ." ".  $dateDelim ;?></span> <span class="normaltxt_11p_blk fontsize_12p"><?php echo $end_date;?></span> </p>
      <div class="spacer10 clearFix"></div>
    <?php
                                                        if($location != '') {
                                                ?>
    <p class="wdh100"> <b>Venue Details:</b><br />
      <span class="normaltxt_11p_blk fontSize_12p"><?php echo $location; ?></span>
      <?php
                                                        }
                                                ?>
    </p>
    <div class="spacer10 clearFix"></div>
    <?php
                                                        if($description != '') {
                                                ?>
    <p class="wdh100"> <b>Additional Details:</b><br />
      <span class="normaltxt_11p_blk fontSize_12p" style="line-height:15px"><?php echo $description;?></span> </p><div class="spacer10 clearFix"></div>
    <?php
                                                        }
                                                ?>
  </div>
  <div class="spacer10 clearFix">&nbsp;</div>
  <div class="mar_full_10p">
    <div class="float_L"><span class="Fnt11 grayFont">Event Type : <?php
                                                        if($fromOthers==0){
                                                        echo "Application Submission Deadline";}
                                                        elseif($fromOthers==1){
                                                        echo "Course Commencement";}
                                                        elseif($fromOthers==2){
                                                        echo "Result Declaration";}
                                                        elseif($fromOthers==3){
                                                        echo "Examination Date";}
                                                        elseif($fromOthers==4){
                                                        echo "Form Issuance";}
                                                        elseif($fromOthers==5){
                                                        echo "General";}
                                                        ?></span> <span class="Fnt11 grayFont">
	<div>Posted under : <?php
                                                if($mainCategoryCsv !== '1'){
                                                        $selectedCatName = '';
							 $categoryArray = explode(",",$mainCategoryCsv);
                                                        foreach($categoryArray as $temp)
                                                        {
                                                                if($temp != 1)
                                                                        $selectedCatName .= $categoryForLeftPanel[$temp][0].' - ';
                                                        }
                                                        $selectedCatName = substr($selectedCatName,0,strlen($selectedCatName)-2);
                                                        echo $selectedCatName;
                                                }else if($mainCategoryCsv == '1'){
                                                        echo "All categories";
                                                }
                                                ?>
	</div>
      </span></div>
    <div class="clear_B lineSpace_20">&nbsp;</div>
    <div class="float_R">
      <div class="grayFont">
        <?php
                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                                $onRedirect = base64_encode('/events/Events/showAddEvent');
                                $onClick = 'showuserLoginOverLay(this,\'EVENTS_EVENTSDETAIL_TOP_ADDEVENT\',\'redirect\',\''.$onRedirect.'\');return false;';
                                                }else {
                                                        if($validateuser['quicksignuser'] == a) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = '';
                                                        }
                                                }
                                        ?>
        <a href="/events/Events/showAddEvent" onClick="<?php echo $onClick; ?>" title="Add an Event">Add an Event</a>&nbsp; <span id="userOperationPlace"> <span style="color:#CCCCCC">|</span>&nbsp;
        <?php
                                                        if(is_array($validateuser) && ($user_id == $validateuser[0]['userid'] || $validateuser[0]['usergroup'] == 'cms' )){
                                                ?>
        <a href="/events/Events/showAddEvent/<?php echo $event_id; ?>" title="Edit Event">Edit Event</a>&nbsp; <span style="color:#CCCCCC">|</span>&nbsp; <a href="#" onClick="deleteEvent(<?php echo $event_id; ?>)"  title="Delete Event">Delete Event</a>&nbsp; <span style="color:#CCCCCC">|</span>&nbsp;
        <?php
                                                        }
                                                    if(!empty($event_id)) {
							if($reportedAbuse>0){ ?>
							<span style="color:#CCCCCC">|</span>&nbsp;
							<span id="abuseLink<?php echo $event_id;?>">Reporte Abuse&nbsp;</span>
							<?php }
							else
							{
							  if(!(($isCmsUser == 1)&&($status_id=="2"))){ 
							    $onClick = "javascript:report_abuse(".$event_id.",".$user_id.",0,0,'Event',".$event_id.",0);";
							?>
							<span id="abuseLink<?php echo $event_id;?>"><a href="#" onclick="<?php echo $onClick; ?>" title="Report Abuse">Report Abuse</a>&nbsp;</span>
							<?php }}

						 ?>
        <?php } ?>
        </span></div>
    </div>
    <div class="clear_B">&nbsp;</div>
<!--    <div class="brdbottom">&nbsp;</div>-->
  </div>
  <div id="eventOperationResponse"></div>
  <!--Start_AbuseForm-->
  <div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $event_id;?>"> </div>
  <div class="showMessages" style="display:none;" id="confirmMsg<?php  echo $event_id; ?>">&nbsp;</div>
  <!--End_AbuseForm-->
</div>
<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<div class="clear_L"></div>
</div>

