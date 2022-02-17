<?php if(count($univData > 0)){ ?>
<?php
foreach($univData as $univId => $courseData){ ?>
<?php
$firstCourseId = reset(array_keys($courseData));
$firstCourseData = $courseData[$firstCourseId];

unset($courseData[$firstCourseId]);
?>
<div class="mb20" data-enhance="false">
	<div class="srcRs-lst" <?php if(count($courseData)==0){?>style="border-bottom:1px solid #ccc;padding-bottom:10px;"<?php }?>>
		<div>
			<div class="srcRs-det clearfix">
				<div class="rsCr-img flLt"><a href="<?php echo $firstCourseData['c'];?>" target="_blank" class="tl" loc="img" lid="<?php echo $firstCourseData['ai'];?>"><img height="90" width="135" src="<?php echo $firstCourseData['ad'];?>" alt="<?php echo htmlentities($firstCourseData['d']);?>"/></a></div>
				<div class="rsCr-inf">
					<p class="src-uni"><strong><a href="<?php echo $firstCourseData['c'];?>" target="_blank" class="tl" loc="utitle" lid="<?php echo $firstCourseData['ai'];?>"><?php echo ($firstCourseData['d']);?></a></strong> <span><?php echo $firstCourseData['g'].', ';?><?php echo $firstCourseData['e'];?></span></p>
					<p><?php echo ucfirst($firstCourseData['h']); ?> University</p>
				</div>
			</div>
			<div class="srcCr-info">
				<a href="<?php echo $firstCourseData['b']?>" target="_blank" class="srcCr-name tl" loc="ctitle" lid="<?php echo $firstCourseData['a'];?>"><?php echo ($firstCourseData['i']);?></a>
				<span class="cr-dur"> <?php echo htmlentities($firstCourseData['j'])." ".(($firstCourseData['j']==1)?str_replace('s', '', $firstCourseData['k']):($firstCourseData['k']));?>, <?php echo $firstCourseData['t']." ".(($firstCourseData['t']=='Masters' || $firstCourseData['t']=='Bachelors')?" Degree":"");?></span>
				<?php if($firstCourseData['n']!='' && $firstCourseData['l'] !=''){?>
				<p>Ranked <?php echo htmlentities($firstCourseData['l']);?> in <a href="<?php echo $firstCourseData['n'];?>" target="_blank" class="tl" loc="rtitle" lid="<?php echo $firstCourseData['a'];?>"><?php echo ($firstCourseData['m']);?></a></p>
				<?php } ?>
				<ul class="cr-dur">
					<li><label>1st year total fees</label> <span><?php echo htmlentities($firstCourseData['o']);?></span></li>
					<li><label>Exams accepted</label> <span><?php echo htmlentities($firstCourseData['examString']);?></span></li>
					<?php if(isset($firstCourseData['otherFields']['12th'])){?>
					<li><label>Class 12th marks</label> <span><?php echo htmlentities($firstCourseData['q']);?> %</span></li>
					<?php }?>
					<?php if(isset($firstCourseData['otherFields']['bachelorsPercentage'])){?>
					<li><label>Bachelor marks</label> <span><?php echo htmlentities($firstCourseData['bachelorString']);?></span></li>
					<?php } ?>
					<?php if(isset($firstCourseData['otherFields']['workEx'])){?>
					<li><label>Work experience</label> <span><?php echo htmlentities(($firstCourseData['s'])==0)?"Required":$firstCourseData['s'];?> <?php echo (($firstCourseData['s'] ==1)?"year":($firstCourseData['s']!=0 && $firstCourseData['s']!='')?"years":'');?></span></li>
					<?php }?>
				</ul>
				<?php
	$today = date("Y-m-d");
    if($firstCourseData['aS']['text'] && $today >= $firstCourseData['aS']['startdate'] && $today <= $firstCourseData['aS']['enddate']) {
                ?>
<div class="Annoncement-Box">
  <strong>Annoncement</strong>
  <div class="Announcement-section">
    <p> <?php echo $firstCourseData['aS']['text'] ?></p>
      <p><?php echo $firstCourseData['aS']['actiontext']?></p>
  </div>
</div>
<?php } ?>
				<div class="compare-bro-sec clearfix">
					<a href="javascript:void(0)" onclick="addRemoveFromShortlist(<?php echo $firstCourseData['a'];?>,'<?php echo $identifier?>','<?php echo $pageType?>',this);" loc="save" lid="<?php echo $firstCourseData['a']; ?>" class="crse-btn tl <?php echo (in_array($firstCourseData['a'],$userShortlistedCourses['courseIds']))?'active':'';?>"><?php echo (in_array($firstCourseData['a'],$userShortlistedCourses['courseIds']))?'Saved':'Save this course'?></a>
					<?php
					if(!is_null($courseObj[$firstCourseData['a']]))
					{
						$desiredCourseId = $courseObj[$firstCourseData['a']]->getDesiredCourseId()?$courseObj[$firstCourseData['a']]->getDesiredCourseId():$courseObj[$firstCourseData['a']]->getLDBCourseId();
					}else{
						$desiredCourseId = null;
					}
					$cd = array( $firstCourseData['a']=>array(
														'desiredCourse' => $desiredCourseId,
														'paid'		    => in_array($firstCourseData['az'],array(1,2,3))?'yes':'no',
														'name'		    => $firstCourseData['i'],
														'subcategory'   => $firstCourseData['ba']
														)
								);

					if($courseObj[$firstCourseData['a']])
						$countryId = $courseObj[$firstCourseData['a']]->getCountryId();
					$brochureDataObj = array(
										   'sourcePage'             => 'searchPageMobile',
										   'courseId'               => $firstCourseData['a'],
										   'courseName'             => $firstCourseData['i'],
										   'universityId'           => $firstCourseData['ai'],
										   'universityName'         => $firstCourseData['d'],
										   'destinationCountryName' => $firstCourseData['e'],
										   'destinationCountryId'   => $countryId,
										   'courseData'	            => base64_encode(json_encode($cd)),
										   'mobile'                 => true,
										   'widget'					=> 'search_page',
										   'pageTitle'				=> 'Search Results',
										   'trackingPageKeyId'		=> 64
									   );
					?>
					<a href="#responseForm" id="test" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?php echo base64_encode(json_encode($brochureDataObj))?>',this);" loc="ebbut" lid="<?php echo $firstCourseData['a'];?>" class="crse-btn2 eb tl"><i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
				</div>
				<?php
				$brochureDataObj['trackingPageKeyId'] = 432;
				$brochureDataObj['courseObj'] = $courseObj[$firstCourseData['a']];
				$brochureDataObj['userRmcCourses'] = $userRmcCourses;
				if($courseObj[$firstCourseData['a']] && $courseObj[$firstCourseData['a']]->showRmcButton()){
					$rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
				}
				?>
			</div>
		</div>
	</div><?php if(count($courseData) >0){?>
	<div class="simCr-col">
		<span class="simTitle">Similar courses at this university</span>
	</div>
	<div class="srcRs-lst brdrTop0">
		<div class="srcRs-det clearfix">
			<ul>
				<?php
				$class = '';
				$courseCount = 0;
				$moreCourseCount = -1;
				foreach ($courseData as $key => $similarTupleData)
				{ ?>
					<li class="<?php echo $class;?>">
		<div>
			<div class="srp-sim">
			<i class="plus-icon" courseId="<?php echo $similarTupleData['a'];?>">+</i>
				<div class="srp-courseTab">
				<a href="<?php echo $similarTupleData['b'];?>" class="srcCr-name tl"  target="_blank" loc="sclink" lid="<?php echo $similarTupleData['a'];?>"><?php echo ($similarTupleData['i']);?></a>
				<span class="cr-dur"> <?php echo htmlentities($similarTupleData['j'])." ".htmlentities(($similarTupleData['j']==1)?str_replace('s', '', $similarTupleData['k']):$similarTupleData['k']);?>, <?php echo $similarTupleData['t']." ".(($similarTupleData['t']=='Masters' || $similarTupleData['t']=='Bachelors')?" Degree":"");?></span>
				</div>
			</div>
		</div>
		<div class="hide course<?php echo $similarTupleData['a'];?>">
			<?php if($similarTupleData['n']!='' && $similarTupleData['l'] !=''){?>
			<p>Ranked <?php echo htmlentities($similarTupleData['l']);?> in <a href="<?php echo $similarTupleData['n'];?>" target="_blank" class="tl" loc="srlink" lid="<?php echo $similarTupleData['a'];?>"><?php echo htmlentities($similarTupleData['m']);?></a></p>
			<?php } ?>
			<ul class="cr-dur">
			<li><label>1st year total fees</label> <span><?php echo htmlentities($similarTupleData['o']);?></span></li>
			<li><label>Exams accepted</label> <span><?php echo htmlentities($similarTupleData['examString']);?></span></li>
			<?php if(isset($similarTupleData['otherFields']['12th'])){?>
			<li><label>Class 12th marks</label> <span><?php echo htmlentities($similarTupleData['q']);?> %</span></li>
			<?php }?>
			<?php if(isset($similarTupleData['otherFields']['bachelorsPercentage'])){?>
			<li><label>Bachelor marks</label> <span><?php echo htmlentities($similarTupleData['bachelorString']);?></span></li>
			<?php } ?>
			<?php if(isset($similarTupleData['otherFields']['workEx'])){?>
			<li><label>Work experience</label> <span><?php echo htmlentities(($similarTupleData['s']== 0)?"Required":$similarTupleData['s']);?> <?php echo (($similarTupleData['s'] ==1)?"year": ($similarTupleData['s']!=0 && $similarTupleData['s']!='')?'years':'');?></span></li>
			<?php }?>
			</ul>
			<div class="compare-bro-sec clearfix">
				<a href="javascript:void(0)" onclick="addRemoveFromShortlist(<?php echo $similarTupleData['a'];?>,'<?php echo $identifier?>','<?php echo $pageType?>',this);" loc="save" lid="<?php echo $similarTupleData['a'];?>" class="crse-btn tl <?php echo in_array($similarTupleData['a'],$userShortlistedCourses['courseIds'])?'active':''?>"><?php echo in_array($similarTupleData['a'],$userShortlistedCourses['courseIds'])?'Saved':'Save this course'?></a>
				<?php
					if(!is_null($courseObj[$similarTupleData['a']]))
					{
						$desiredCourseId = $courseObj[$similarTupleData['a']]->getDesiredCourseId()?$courseObj[$similarTupleData['a']]->getDesiredCourseId():$courseObj[$similarTupleData['a']]->getLDBCourseId();
					}else{
						$desiredCourseId = null;
					}
					$cd = array( $similarTupleData['a']=>array(
														'desiredCourse' => $desiredCourseId,
														'paid'		    => in_array($similarTupleData['az'],array(1,2,3))?'yes':'no',
														'name'		    => $similarTupleData['i'],
														'subcategory'   => $similarTupleData['ba']
														)
								);

					if($courseObj[$similarTupleData['a']])
						$countryId = $courseObj[$similarTupleData['a']]->getCountryId();
					$brochureDataObj = array(
										   'sourcePage'             => 'searchPageMobile',
										   'courseId'               => $similarTupleData['a'],
										   'courseName'             => $similarTupleData['i'],
										   'universityId'           => $similarTupleData['ai'],
										   'universityName'         => $similarTupleData['d'],
										   'destinationCountryName' => $similarTupleData['e'],
										   'destinationCountryId'   => $countryId,
										   'courseData'	            => base64_encode(json_encode($cd)),
										   'mobile'                 => true,
										   'widget'					=> 'search_page',
										   'pageTitle'				=> 'Search Results',
										   'trackingPageKeyId'		=> 64
									   );
				?>
				<a href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?php echo base64_encode(json_encode($brochureDataObj))?>',this);" loc="ebbut" lid="<?php echo $similarTupleData['a'];?>" class="crse-btn2 tl"><i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
			</div>
			<?php
			$brochureDataObj['trackingPageKeyId'] = 432;
			$brochureDataObj['courseObj'] = $courseObj[$similarTupleData['a']];
			$brochureDataObj['userRmcCourses'] = $userRmcCourses;
			if($courseObj[$similarTupleData['a']] && $courseObj[$similarTupleData['a']]->showRmcButton()){
				$rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
			}?>
		</div>
		</li>
		<?php
		unset($courseData[$similarTupleData['a']]);
		$courseCount++;
		if($courseCount>=2){
		$class = 'hide hiddenSimlarCourse';
		$moreCourseCount++;
		}
		} ?>
		</ul>
		</div>
	</div>
	<?php if($moreCourseCount >0){?>
	<div class="src-tac moreCourseButton">
	<a href="javaScript:void(0)" class="mrCr-btn"><span class="moreText">+</span><?php echo $moreCourseCount;?> more course<?php echo (($moreCourseCount >1)?"s":'');?></a>
	</div>
	<?php
	}
	} ?>
</div>
	<?php } ?>
	<?php } ?>
