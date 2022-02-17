<?php 
$truncateStrLength = isset($truncateStrLength) ? $truncateStrLength : 33;
foreach($courseList[0]['courses'] as $course){
    if(empty($course['id'])) {  continue; }
    $courseId 		= $course['id'];
    $courseName 	= ucwords($course['title']);
    $collegeCity	= $course['locationArr'][0]['city_name'];
    $collegeCountry = $course['locationArr'][0]['country_name'];
    if($countrySelected != '') {
        for($locationCount = 0; $locationCount < count($course['locationArr']); $locationCount++){
            if($course['locationArr'][$locationCount]['country_id'] == $countrySelected) {
                $collegeCity	= $course['locationArr'][$locationCount]['city_name'];
                $collegeCountry = $course['locationArr'][$locationCount][$country_name];
                break;
            }
        }
    }
    $url = $course['url'];
    $location = $collegeCity;
    if($collegeCity != '' && $collegeCountry!= '') {
        $location .= ' - ';
    }
    $location .= $collegeCountry;
    $sponsoredResult = (isset($course['isSponsored'])  && ($course['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
    $truncateStrLengthForRecord = (isset($course['isSponsored'])  && ($course['isSponsored'] == "true")) ? $truncateStrLength-3 : $truncateStrLength;
    $sponsoredResultMargin = (isset($course['isSponsored'])  && ($course['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
    $courseDisplayName = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
    $locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
    $locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
    $collegeUrl = html_entity_decode($course['instituteUrl']);
    $collegeName = html_entity_decode($course['institute_name']);
    $collegeNameDisplayText = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
    $collegeNameDisplayText = $collegeNameDisplayText =='' ? '&nbsp;' : $collegeNameDisplayText;

?>		
    <div class="w49_per float_L">
	    <span class="normaltxt_11p_blk fontSize_12p arial">
		    <div class="row">
			    <div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>"><img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/></div>
				<div class="float_L">
				    <div class="normaltxt_11p_blk_arial">
					    <a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $courseName ;?>"><?php echo $courseDisplayName; ?></a>
					</div>
					<div class="normaltxt_11p_blk_arial">
					    <a href="<?php echo $collegeUrl; ?>" title="<?php echo $collegeName; ?>" class="blackFont"><?php echo $collegeNameDisplayText; ?></a>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
				</div>
                <div class="clear_L"></div>
			</div>
		</span>
    </div>
<?php } ?>
