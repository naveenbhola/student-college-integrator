<?php
$config = array();
$config['crRejectedReasonMapping'] = array(5 => 1);
$config['crLimitForMobileNo'] = 2;

$config['collegeReviewModerators'] = array(
	'7349973' => 'cue.content1@gmail.com',
	'7339082' => 'cue.content2@gmail.com',
	'7339359' => 'cue.content3@gmail.com',
	'7339369' => 'cue.content4@gmail.com',
	'7339424' => 'cue.content5@rediffmail.com',

	'7568260' => 'reviewmoderator3@gmail.com',
	'7568263' => 'reviewmoderator4@gmail.com',
	'7568267' => 'reviewmoderator5@gmail.com',
	'7568273' => 'reviewmoderator6@gmail.com',
	'7568276' => 'reviewmoderator7@gmail.com',
	'7568279' => 'reviewmoderator8@gmail.com',
	'7568139' => 'reviewmoderator1@gmail.com',
	'7568282' => 'reviewmoderator9@gmail.com',
	'7568285' => 'reviewmoderator10@gmail.com',
	'7568256' => 'reviewmoderator2@gmail.com'
);

$config['whiteListEmailForFilter'] = array('abhijit.bhowmick@shiksha.com','moderator@shiksha.com');

$config['moderatorMapWithDigit'] = array();

//key in moderatorMapWithDigit array is userId and value is college review main table id

//internal
$config['moderatorMapWithDigit']['7568260'] = array('tens'=>array(1),'ones'=>array(1,2,3,4,5));
$config['moderatorMapWithDigit']['7568263'] = array('tens'=>array(2),'ones'=>array(1,2,3,4,5));
$config['moderatorMapWithDigit']['7568267'] = array('tens'=>array(3),'ones'=>array(1,2,3,4,5));
$config['moderatorMapWithDigit']['7568273'] = array('tens'=>array(4),'ones'=>array(1,2,3,4,5));
$config['moderatorMapWithDigit']['7568276'] = array('tens'=>array(5),'ones'=>array(1,2,3,4,5));

$config['moderatorMapWithDigit']['7568279'] = array('tens'=>array(1),'ones'=>array(6,7,8,9,0));
$config['moderatorMapWithDigit']['7568139'] = array('tens'=>array(2),'ones'=>array(6,7,8,9,0));
$config['moderatorMapWithDigit']['7568282'] = array('tens'=>array(3),'ones'=>array(6,7,8,9,0));
$config['moderatorMapWithDigit']['7568285'] = array('tens'=>array(4),'ones'=>array(6,7,8,9,0));
$config['moderatorMapWithDigit']['7568256'] = array('tens'=>array(5),'ones'=>array(6,7,8,9,0));


//external
$config['moderatorMapWithDigit']['7349973'] = array('tens'=>array(6),'ones'=>array(1,2,3,4,5,6,7,8,9,0));
$config['moderatorMapWithDigit']['7339082'] = array('tens'=>array(7),'ones'=>array(1,2,3,4,5,6,7,8,9,0));
$config['moderatorMapWithDigit']['7339359'] = array('tens'=>array(8),'ones'=>array(1,2,3,4,5,6,7,8,9,0));
$config['moderatorMapWithDigit']['7339369'] = array('tens'=>array(9),'ones'=>array(1,2,3,4,5,6,7,8,9,0));
$config['moderatorMapWithDigit']['7339424'] = array('tens'=>array(0),'ones'=>array(1,2,3,4,5,6,7,8,9,0));


$config['showAllReviewsUserId'] = array('11'=>1,'670062'=>1,'6470972'=>1);


$config['rejectionScore'] = 0.2;

$config['aggregateRatingDisplayOrder'] = array('avgSalaryPlacementRating' => 'Placements','campusFacilitiesRating' => 'Infrastructure','facultyRating' => 'Faculty & Course Curriculum','crowdCampusRating' => 'Crowd & Campus Life','moneyRating' => 'Value for Money');
$config['intervalsDisplayOrder'] = array('4-5' => '>4-5','3-4' => '>3-4','2-3' => '>2-3','1-2' => '&nbsp;&nbsp;1-2');
$config['ratingFilterDisplayMapping'] = array('5' => 'All Stars', '4' => '>4 - 5 Star','3' => '>3 - 4 Star','2' => '>2 - 3 Star','1' => '&nbsp;&nbsp;1 - 2 Star');

$config['crMasterMappingToName'] = array(	1 => moneyRating,
										2 => crowdCampusRating,
										3 => avgSalaryPlacementRating,
										4 => campusFacilitiesRating,
										5 => facultyRating,
										6 => campusFacilitiesRating,
										7 => avgSalaryPlacementRating,
										8 => campusFacilitiesRating,
										9 => campusFacilitiesRating,
										10 => avgSalaryPlacementRating);
?>
