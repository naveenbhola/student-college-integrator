    <ul class="student-mentors-list">
	<?php
	$i = 0;
	foreach($mentorListData as $mentorList)
	{
		if($i%2 == 0)
			$cssClass = 'flLt student-mentor-col';
		else
			$cssClass = 'flRt student-mentor-col';
	?>
	<?php if($i%2 == 0){ ?><li> <?php } ?>
		<div class="<?=$cssClass?>">
		<div class="student-mentor-img flLt">
		<?php 
		if($mentorList['imageURL'] != ''){
			$imageUrl = $mentorList['imageURL'];
		}
		else
			$imageUrl = SHIKSHA_HOME.'/public/images/campusAmbassador/mentor_default_image.png';
			?>

			<img width="116" height="141" alt="student" src="<?=$imageUrl?>">
		</div>
		<div class="student-mentor-detail">
			<div style="padding:10px">
			<?php 
			$name = $totalMentor['mentorInformation'][$mentorList['userId']]['firstname'].' '.$totalMentor['mentorInformation'][$mentorList['userId']]['lastname'];

			if($mentorList['semester'] == '1' || $mentorList['semester'] == '2')
			{
				$yearPersuing =  1;
				$superscript = 'st';
			}
			elseif ($mentorList['semester'] == '3' || $mentorList['semester'] == '4') {
				$yearPersuing =  2;
				$superscript = 'nd';
			}
			elseif ($mentorList['semester'] == '5' || $mentorList['semester'] == '6') {
				$yearPersuing =  3;
				$superscript = 'rd';
			}
			elseif ($mentorList['semester'] == '7' || $mentorList['semester'] == '8')
			{
				$yearPersuing =  4;
				$superscript = 'th';
			}
			?>
				<a style="text-decoration:none;"><strong class="font-14"><?= strlen($name)>30 ? substr($name, 0, 30).'..' : $name; ?></strong></a><span class="year-title"> <?= $yearPersuing != '' ?$yearPersuing : ''?><sup><?= $superscript != '' ?$superscript : ''?></sup> Yr</span>
			<ul>
			<li>
				<label>College:</label>
			    <a href="<?=$mentorList['instituteURL']?>"  target="_blank"><?= strlen($mentorList['instituteName'])>40 ? substr($mentorList['instituteName'], 0, 40).'..' : $mentorList['instituteName']?></a>
			</li>
			<li>
				<label>Branch:</label>
			    <a href="<?=$mentorList['courseURL']?>" target="_blank"><?= strlen($mentorList['courseName'])>40 ? substr($mentorList['courseName'], 0, 40).'..' : $mentorList['courseName']?></a>
			</li>
			<li>
				<label>City:</label>
			    <?=$mentorList['city']?>
			</li>
		    </ul>
			</div>
		    <div class="mentee-answer-list clear-width">
			<div style="border-right:1px solid #e8e8e8;" class="answer-count-col">
				<span class="mentee-count-clr"><?=$mentorList['menteeCount']?></span> Mentees
			</div>
			<div class="answer-count-col">
				<span class="mentee-count-clr"><?= $totalMentor['mentorAnsCount'][$mentorList['userId']]['totalAns'] >0 ? $totalMentor['mentorAnsCount'][$mentorList['userId']]['totalAns'] : 0?></span> Answers
			</div>
		    </div>
		</div>
	    </div>
	<?php if($i%2 != 0){ ?></li> <?php } ?>
	<?php $i++;}?>
    </ul>
    