<?php
if($isAbroadRecommendation) {
  $image_url = SHIKSHA_STUDYABROAD_HOME.'/public/images/MOneRecMailer/';
} else {
  $image_url = SHIKSHA_HOME.'/public/images/MOneRecMailer/';
}

$recommendation_id = $recommendation_ids[$recommendationWidgets['recommendationBasicInfo']['instituteID']];
if($isAbroadRecommendation) {
  $base_url = SHIKSHA_STUDYABROAD_HOME."/recommendations/";
} else {
  $base_url = SHIKSHA_HOME."/recommendations/";
}
$course_url = $base_url."course/$recommendation_id/0/1";

$data = array();
$data['image_url'] = $image_url;
$data['course_url'] = $course_url;
?>

<?php
if($algoUsed == 'also_viewed' || $algoUsed == 'similar_institutes') {
    echo 'You showed interest in <strong>'.$courseDetails['courseName'].'</strong> at <strong>';
    
    if($isAbroadRecommendation) {
	echo $courseDetails['universityName'].'</strong>, <strong>'.$courseDetails['countryName'].'</strong>.';
    }
    else {
	echo $courseDetails['instituteName'].'</strong>.';
    }
    
    if ($algoUsed == 'also_viewed') {
	echo ' Students who showed interest in the above course were also interested in the following:</td>';
    }
    else if($algoUsed == 'similar_institutes') {
	echo ' We thought you may be interested in the following similar course:</td>';
    }
}
else if($algoUsed == 'collaborative_profile_based' || $algoUsed == 'profile_based') {
    $cityClause = '';
    if($educationInterest['city'] != NULL) {
	$cityClause = '</strong> in <strong>'.$educationInterest['city'].'</strong>, India ';
    }
    else {
	$cityClause = '</strong> ';
    }
    echo 'You showed interest in <strong>'.$educationInterest['coursename'].' institutes'.$cityClause.'and we thought you may also be interested in learning more about the following institute and course :</td>';
}
?>

</tr>
<tr>
    <td height="10"></td>
</tr>
<tr>
    <td bgcolor="#efefef" valign="top" style="border:1px #dbdbdb solid">
    <table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:500px;">
    <tr>
	<td width="500">
	    <table width="155" border="0" cellspacing="0" cellpadding="0" align="right">
	    <?php if($recommendationWidgets['recommendationBasicInfo']['photoCount'] || $recommendationWidgets['recommendationBasicInfo']['videoCount']) { ?>
		<tr>
		    <td width="145" height="26" align="right" style="font-family:Arial, Helvetica, sans-serif;color:#b3b3b3;"><?php if($recommendationWidgets['recommendationBasicInfo']['photoCount']) { echo '<a href="'.$course_url.'~recommendation<!-- #widgettracker --><!-- widgettracker# -->" title="'.$recommendationWidgets['recommendationBasicInfo']['photoCount'].' Photos" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#0065e8;text-decoration:none;">'.$recommendationWidgets['recommendationBasicInfo']['photoCount'].' Photos</a>'; } ?><?php if($recommendationWidgets['recommendationBasicInfo']['videoCount']) { if($recommendationWidgets['recommendationBasicInfo']['photoCount']) { echo ' | '; } echo '<a href="'.$course_url.'~recommendation<!-- #widgettracker --><!-- widgettracker# -->" title="'.$recommendationWidgets['recommendationBasicInfo']['videoCount'].' Videos" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#0065e8;text-decoration:none;">'.$recommendationWidgets['recommendationBasicInfo']['videoCount'].' Videos</a>'; } ?></td>
		    <td width="10"></td>
		</tr>
	    <?php } ?>
	    </table>
	</td>
    </tr>
    <tr>
	<td>
	    <div style="width:100%">
		<a target="_blank" href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->"><img style="width:100%" src="<?php if(strlen($recommendationWidgets['recommendationBasicInfo']['instituteLogoURL'])) { echo $recommendationWidgets['recommendationBasicInfo']['instituteLogoURL']; } else { echo $image_url.'institute_img.jpg'; } ?>" vspace="0" hspace="0" align="left" alt="Image cannot be displayed" style="max-width:500px;width:inherit;font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#999999" /></a>
	    </div>
	</td>
    </tr>
    <tr>
	<td valign="top">
	<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:500px;">
	<tr>
	    <td width="31"><img src="<?php echo $image_url; ?>spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
	    <td width="490" valign="top">
	    <table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:490px;">
	    <tr>
		<td width="490" valign="top">
		<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:490px;">
		<tr>
		    <td width="490" height="12"></td>
		</tr>
		<tr>
		    <td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#474a4b;"><strong><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" title="<?php echo $recommendationWidgets['recommendationBasicInfo']['instituteName']; ?>" style="font-family:Arial, Helvetica, sans-serif;font-size:17px;text-decoration:none;color:#2170e8;"><?php echo $recommendationWidgets['recommendationBasicInfo']['instituteName']; ?></a></strong>, <?php echo $recommendationWidgets['recommendationBasicInfo']['instituteCityName']; ?> <?php if($isAbroadRecommendation) { echo ' | '.$recommendationWidgets['recommendationBasicInfo']['instituteCountryName']; } ?><br />
    <?php if($recommendationWidgets['recommendationBasicInfo']['typeOfInstitute']) { echo $recommendationWidgets['recommendationBasicInfo']['typeOfInstitute']; } ?>
    <?php if($recommendationWidgets['recommendationBasicInfo']['establishYear']) { if($isAbroadRecommendation) { echo ', '; } echo 'Established in '.$recommendationWidgets['recommendationBasicInfo']['establishYear']; } ?>
    </td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <?php if($recommendationWidgets['recommendationBasicInfo']['alumniRating']) { ?>
  <tr>
    <td valign="top">
    <table width="110" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#474a4b;">
  <tr>
    <td width="110">Alumni Reviews</td>
  </tr>
    <tr>
    <td height="5"></td>
  </tr>
</table>
	<table width="220" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <?php
	$alumniRating = round($recommendationWidgets['recommendationBasicInfo']['alumniRating']);
	
	for($rating = 1; $rating <= 5; $rating++) {
	    $star = $image_url.'green_icon.gif';
	    if($rating > $alumniRating) {
		$star = $image_url.'grey_icon.gif';
	    }
	    
	    echo '<td width="21"><img src="'.$star.'" width="21" height="17" vspace="0" hspace="0" align="left" /></td>';
	}
    ?>
    <td width="115" align="right" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#888888;"><?php echo $recommendationWidgets['recommendationBasicInfo']['alumniRating']; ?>/5 (<?php echo $recommendationWidgets['recommendationBasicInfo']['alumniReviews']; ?> reviews)</td>
  </tr>
</table>

    </td>
  </tr>
  <?php } ?>
  <tr>
    <td height="12"></td>
  </tr>
  <tr>
    <td valign="top">
    <table width="156" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td height="34" bgcolor="#ffda3e" align="center" style="border:1px #e8b363 solid;"><strong><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="View Contact details" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#474a4b;text-decoration:none;display:block;line-height:34px;">View Contact details</a></strong></td>
  </tr>
</table>

    </td>
  </tr>
  <tr>
    <td height="17"></td>
  </tr>
</table>
	</td>
  </tr>
  <tr>
    <td>
    <div style="width:100%;">
    <img src="<?php echo $image_url; ?>divider.gif" vspace="0" hspace="0" align="left" style="max-width:500px;width:inherit;" />
    </div>
    </td>
  </tr>
  <tr>
    <td valign="top">
    <table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:458px;">
  <tr>
    <td width="490" height="12"></td>
  </tr>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#474a4b;"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" title="<?php echo $recommendationWidgets['recommendationBasicInfo']['courseName']; ?>" style="font-family:Arial, Helvetica, sans-serif;font-size:17px;text-decoration:none;color:#2170e8;"><?php echo $recommendationWidgets['recommendationBasicInfo']['courseName']; ?></a><br />
    <?php if(!$isAbroadRecommendation && strlen(trim($recommendationWidgets['recommendationBasicInfo']['courseApprovals']))) { echo $recommendationWidgets['recommendationBasicInfo']['courseApprovals'].' | '; } ?>
    <?php echo 'Course Duration : '.$recommendationWidgets['recommendationBasicInfo']['courseDuration']; ?>
    <?php if($isAbroadRecommendation && !$recommendationWidgets['recommendationBasicInfo']['hasDummyDepartment']) { echo ' | Offered by : '.$recommendationWidgets['recommendationBasicInfo']['departmentName']; } ?>
    </td>
  </tr>
  <tr>
    <td height="12"></td>
  </tr>
  
  <?php if(!$isAbroadRecommendation) { ?>
  <tr>
    <td valign="top">
    <table width="156" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td height="34" bgcolor="#ffda3e" align="center" style="border:1px #e8b363 solid;"><strong><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="Know more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#474a4b;text-decoration:none;display:block;line-height:34px;">Know more</a></strong></td>
  </tr>
</table>

    </td>
  </tr>
  <?php } ?>
  
  <tr>
    <td height="17"></td>
  </tr>
</table>
    </td>
  </tr>
  
  <?php if($isAbroadRecommendation) { ?>
  <tr>
    <td valign="top">
    <table border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="#ffffff" style="max-width:440px;border:1px #dbdbdb solid">
  <tbody><tr>
    <td width="440" height="38" style="border-bottom:1px #dbdbdb solid;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b"><img src="<?php echo $image_url; ?>course_icon.gif" width="39" height="16" vspace="0" hspace="0" align="absmiddle"><strong>Course Description</strong></td>
  </tr>
  <tr>
    <td valign="top">
    <table border="0" cellspacing="0" cellpadding="0" align="left" style="max-width:440px;">
  <tbody><tr>
    <td width="32" height="15"></td>
    <td width="410"></td>
    <td></td>
  </tr>
    
    <tr>
    <td valign="top"></td>
    <td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#474a4b"><?php echo $recommendationWidgets['recommendationBasicInfo']['courseDescription']; ?></td>
      <td width="10"></td>
  </tr>
    
    <tr>
    <td height="15"></td>
    <td></td>
    <td></td>
  </tr>
    
  <tr>
    <td></td>
    <td valign="top">
    <table width="156" border="0" cellspacing="0" cellpadding="0" align="left">
  <tbody><tr>
    <td height="34" bgcolor="#ffda3e" align="center" style="border:1px #e8b363 solid;"><strong><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="Know more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#474a4b;text-decoration:none;display:block;line-height:34px;">Know more</a></strong></td>
  </tr>
</tbody></table>
    </td>
    <td></td>
  </tr>
  <tr>
    <td height="16"></td>
    <td></td>
    <td></td>
  </tr>
</tbody></table>

    </td>
  </tr>
</tbody></table>

    </td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <?php
	}
  if(count($recommendationWidgetPositions)) {
    $currentRow = 0;
    $currentColumn = 0;
    $newRow = false;
    foreach($recommendationWidgetPositions as $widgetName => $widgetPosition) {
	$widgetRow = $widgetPosition['row'];
	$widgetColumn = $widgetPosition['column'];
	
	if($currentColumn > $widgetColumn) {
	    $newRow = false;
	    echo '</td></tr>';
	}
	$currentColumn = $widgetColumn;
	
	if($currentRow != $widgetRow) {
	    $newRow = true;
	    $currentRow = $widgetRow;
	    echo '<tr><td valign="top">';
	}
	
	if(isset($recommendationWidgets[$widgetName])) {
	    $this->load->view('MailerWidgets/RecoMailerWidgets/'.$widgetName, $data);
	}
	
	
    }
    
    if($newRow == true) {
	echo '</td></tr>';
    }
  }
  ?>
</table>

    </td>
    <td width="21"><img src="<?php echo $image_url; ?>spacer.gif" width="6" height="1" vspace="0" hspace="0" align="right" /></td>
  </tr>
</table>

    </td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
</table>

    </td>
  </tr>
    <tr>
    <td height="15"></td>
  </tr>
    
    <?php if($isAbroadRecommendation && !empty($recommendationWidgets['moreCourseInfo'])) { ?>
	<tr>
    <td valign="top">
    <table cellspacing="0" cellpadding="0" border="0" align="center" style="max-width:500px;border:1px #dbdbdb solid">
  <tbody><tr>
    <td width="500" valign="top" bgcolor="#f1f1f1">
    <table cellspacing="0" cellpadding="0" border="0" align="left" style="max-width:458px;">
  <tbody><tr>
    <td width="44" height="15"></td>
    <td width="414"></td>
  </tr>
  <tr>
    <td valign="top"><img width="46" vspace="4" hspace="0" align="left" height="18" src="<?php echo $image_url; ?>more_info_icon.gif"></td>
    <td style="font-family:Arial, Helvetica, sans-serif;font-size:18px;color:#474a4b;">More info on about this course </td>
  </tr>
  <tr>
    <td height="15"></td>
    <td></td>
  </tr>
</tbody></table>
	</td>
  </tr>
    <tr>
    <td valign="top">
    <table cellspacing="0" cellpadding="0" border="0" align="left" style="max-width:490px;">
  <tbody>
    <tr>
    <td width="490" height="15"></td>
  </tr>
    
    <?php
    $currentRow = 0;
    $currentColumn = 0;
    $newRow = false;
    foreach($recommendationWidgets['moreCourseInfo']['moreInfoWidgetPositions'] as $widgetName => $widgetPosition) {
	$widgetRow = $widgetPosition['row'];
	$widgetColumn = $widgetPosition['column'];
	    
	if($currentColumn > $widgetColumn) {
	    $newRow = false;
	    echo '</td></tr>';
	}
	$currentColumn = $widgetColumn;
	
	if($currentRow != $widgetRow) {
	    $newRow = true;
	    $currentRow = $widgetRow;
	    echo '<tr><td valign="top">';
	}
	    
	if(isset($recommendationWidgets['moreCourseInfo'][$widgetName])) {
    ?>
	<table width="230" cellspacing="0" cellpadding="0" border="0" align="left">
	    <tbody>
		<tr>
		    <td width="30"></td>
		    <td width="200" valign="top">
			<table width="200" cellspacing="0" cellpadding="0" border="0" align="left">
			<tbody>
			    <tr>
				<td bgcolor="#ffda3e" align="center" height="34" style="border:1px #e8b363 solid;"><strong><a title="Know more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#474a4b;text-decoration:none;display:block;line-height:34px;" href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->"><?php echo $recommendationWidgets['moreCourseInfo'][$widgetName]['text']; ?></a></strong></td>
			    </tr>
			</tbody>
			</table>
		    </td>
		</tr>
		<tr>
		    <td height="15"></td>
		    <td></td>
		</tr>
	    </tbody>
	</table>
    <?php
	}
	
	if($newRow == true) {
	    //echo '</td></tr>';
	}
    }
    ?>
    </td>
  </tr>
</tbody></table>
    </td>
  </tr>
	 </tbody>
      </table>
   </td>
</tr>
    <?php } ?>
    
    <tr>
    <td valign="top">
    <?php
    if(isset($recommendationWidgets['campusAmbassador'])) {
	$this->load->view('MailerWidgets/RecoMailerWidgets/campusAmbassador', $data);
    }
    ?>
</td>
  </tr>
      <tr>
    <td height="15"></td>
  </tr>
      
      
      
      