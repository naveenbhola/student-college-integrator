<?php
function getAbusePointsFromLevelId($levelId)
{
	$abusePointsLevelIdMapping = array(
		'1' => 1,
		'2' => 1,
		'3' => 1,
		'4' => 1,
		'5' => 1,
		'6' => 2,
		'7' => 2,
		'8' => 2,
		'9' => 2,
		'10' => 2,
		'11' => 3,
		'12' => 3,
		'13' => 3,
		'14' => 3,
		'15' => 3,
		'16' => 4,
		'17' => 4,
		'18' => 4
		);
	$res = $abusePointsLevelIdMapping[$levelId];
	return $res;
}

function getLevelNameFromLevelId($levelId){
        $LevelNameIdMapping = array(
                '1' => 'Beginner-Level 1',
                '2' => 'Beginner-Level 2',
                '3' => 'Beginner-Level 3',
                '4' => 'Beginner-Level 4',
                '5' => 'Beginner-Level 5',
                '6' => 'Contributor-Level 6',
                '7' => 'Contributor-Level 7',
                '8' => 'Contributor-Level 8',
                '9' => 'Contributor-Level 9',
                '10' => 'Contributor-Level 10',
                '11' => 'Guide-Level 11',
                '12' => 'Guide-Level 12',
                '13' => 'Guide-Level 13',
                '14' => 'Guide-Level 14',
                '15' => 'Guide-Level 15',
                '16' => 'Scholar-Level 16',
                '17' => 'Scholar-Level 17',
                '18' => 'Scholar-Level 18'
                );
	if($LevelNameIdMapping[$levelId]){
		return $LevelNameIdMapping[$levelId];
	}
	else{
		return "Beginner-Level 1";
	}
}

function getPointRangeForLevel($levelId){
        $LevelPointRangeMapping = array(
                '1' => array('lowerLimit'=>'-10000000','upperLimit'=>'24'),
                '2' => array('lowerLimit'=>'25','upperLimit'=>'49'),
                '3' => array('lowerLimit'=>'50','upperLimit'=>'99'),
                '4' => array('lowerLimit'=>'100','upperLimit'=>'199'),
                '5' => array('lowerLimit'=>'200','upperLimit'=>'399'),
                '6' => array('lowerLimit'=>'400','upperLimit'=>'699'),
                '7' => array('lowerLimit'=>'700','upperLimit'=>'1149'),
                '8' => array('lowerLimit'=>'1150','upperLimit'=>'1749'),
                '9' => array('lowerLimit'=>'1750','upperLimit'=>'2499'),
                '10' => array('lowerLimit'=>'2500','upperLimit'=>'3499'),
                '11' => array('lowerLimit'=>'3500','upperLimit'=>'4999'),
                '12' => array('lowerLimit'=>'5000','upperLimit'=>'7499'),
                '13' => array('lowerLimit'=>'7500','upperLimit'=>'11499'),
                '14' => array('lowerLimit'=>'11500','upperLimit'=>'17499'),
                '15' => array('lowerLimit'=>'17500','upperLimit'=>'27499'),
                '16' => array('lowerLimit'=>'27500','upperLimit'=>'42499'),
                '17' => array('lowerLimit'=>'42500','upperLimit'=>'67499'),
                '18' => array('lowerLimit'=>'67500','upperLimit'=>'99999999999')
                );
        if($LevelPointRangeMapping[$levelId]){
                return $LevelPointRangeMapping[$levelId];
        }
        else{
                return array('lowerLimit'=>'-10000000','upperLimit'=>'24');
        }

}

function getLevelFromPoints($points,$HQCount){
        if(!isset($points) || empty($points)){
                return '1';
        }
	switch($points){	//For cases, where points are less than 35000
		case $points < 25: return '1';
                case $points < 50: return '2';
                case $points < 100: return '3';
                case $points < 200: return '4';
                case $points < 400: return '5';
                case $points < 700: return '6';
                case $points < 1150: return '7';
                case $points < 1750: return '8';
                case $points < 2500: return '9';
                case $points < 3500: return '10';		
	}

	if($HQCount >= 100){   	//Eligible to be Guide with Points greater than 3500 and HQ Count > 100
		switch($points){
			case $points < 5000: return '11';
			case $points < 7500: return '12';
			case $points < 11500: return '13';
			case $points < 17500: return '14';
			case $points < 27500: return '15';
		}
	}
	else{	//If HQ count is less than 25, but points are greater than 3500.
		return '10';
	}

	//At this point, HQ count will be greater than 100 and Points > 30000
	if($HQCount >= 1000 && $points >= 67500){        //Eligible for Level 18
		return '18';
	}
	else if($HQCount >= 500 && $points >= 42500){	//Eligible for Level 17
		return '17';
	}
	else if($HQCount >= 250 && $points >= 27500){	//Eligible for Level 16
		return '16';
	}
	else{
		return '15';
	}

}
function getExpertLevels()
{
        $levelNames = array(
                'Beginner-Level 1',
                'Beginner-Level 2',
                'Beginner-Level 3',
                'Beginner-Level 4',
                'Beginner-Level 5',
                'Contributor-Level 6',
                'Contributor-Level 7',
                'Contributor-Level 8',
                'Contributor-Level 9',
                'Contributor-Level 10',
                'Guide-Level 11',
                'Guide-Level 12',
                'Guide-Level 13',
                'Guide-Level 14',
                'Guide-Level 15',
                'Scholar-Level 16',
                'Scholar-Level 17',
                'Scholar-Level 18'
                );
        foreach ($levelNames as $key => $value) {
                $res[$key]['levelName'] = $value;
        }
        return $res;
}
?>
