<?php 
foreach($collegeList[0]['institutes'] as $college){
    if(empty($college['id'])) {  continue; }
    $collegeId 		= $college['id'];
    $collegeName 	= ucwords($college['title']);
    $collegeCity	= $college['locationArr'][0]['city_name'];
    $collegeCountry = $college['locationArr'][0]['country_name'];
    if($countrySelected != '') {
        for($locationCount = 0; $locationCount < count($college['locationArr']); $locationCount++){
            if($college['locationArr'][$locationCount]['country_id'] == $countrySelected) {
                $collegeCity	= $college['locationArr'][$locationCount]['city_name'];
                $collegeCountry = $college['locationArr'][$locationCount][$country_name];
                break;
            }
        }
    }
    $url = $college['url'];
    $location = $collegeCity;
    if($collegeCity != '' && $collegeCountry!= '') {
        $location .= ' - ';
    }
    $location .= $collegeCountry;
    $sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
    $truncateStrLengthForRecord = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? $truncateStrLength-3 : $truncateStrLength;
    $sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
    $collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
    $locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
    $locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
    $courseId = $college['courseArr'][0]['course_id'];
    $courseUrl = html_entity_decode($college['courseArr'][0]['url']);
    $courseName = html_entity_decode($college['courseArr'][0]['title']);
    $courseNameDisplayText = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
    $courseNameDisplayText = $courseNameDisplayText=='' ? '&nbsp;' : $courseNameDisplayText;

?>		
    <div class="w49_per float_L">
	    <span class="normaltxt_11p_blk fontSize_12p arial">
		    <div class="row">
			    <div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>"><img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/></div>
				<div class="float_L">
				    <div class="normaltxt_11p_blk_arial">
					    <a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><?php echo $collegeDisplayName; ?></a>
					</div>
					<div class="normaltxt_11p_blk_arial">
					    <a href="<?php echo $courseUrl; ?>" title="<?php echo $courseName; ?>" class="blackFont"><?php echo $courseNameDisplayText; ?></a>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
				</div>
                <div class="clear_L"></div>
			</div>
		</span>
    </div>
<?php } ?>
