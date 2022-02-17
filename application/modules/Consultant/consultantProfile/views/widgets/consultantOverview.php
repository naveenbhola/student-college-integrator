<div class="overview-details flLt overview-tab" style="width:100%;">
	<h2>About <?php echo htmlentities(formatArticleTitle($consultantObj->getName(),20));?></h2>
	<div id ="consultantDescription" class="cons-scrollbar1 scrollbar1" style="padding-bottom:10px;">	
		<div class="cons-scrollbar scrollbar" style="visibility:hidden; left: 8px">
			<div class="track">
				<div class="thumb"></div>
			</div>
		</div>
		<div class="viewport" style="height: 185px;">
			<div class="overview clearwidth dyanamic-content marginLft" style="width:98%; margin-bottom:5px;">
				<?php echo $consultantObj->getDescription();?>
			</div>
		</div>
	</div>
		
	<div class="clearwidth">
		<ul class="rep-list">
		<?php if($collegesRepresentedTabFlag){?>
			<li onclick="$j('#leftnav-collegeRepresented').trigger('click'); studyAbroadTrackEventByGA('ABROAD_CONSULTANT_PAGE', 'collegeRepresentedTab');" style="cursor:pointer;">
				<div class="college-rep-sec">
					<div class="college-info-box consultant-info-title">
						<strong>Colleges</strong>
					</div>
					<?php $v = reset($collegesRepresentedTabData);?>
					<div class="college-info-box">
						<img src="<?php echo $v['logo_link'];?>" width="71" height="26" alt="<?php echo htmlentities(($v['univName']));?>" />
					</div>
					<div class="college-info-box">
						<?php echo htmlentities(formatArticleTitle($v['univName'],30));?>
					</div>
					<?php 
					 if( count($collegesRepresentedTabData) > 1)
					 { ?>
					<div class="college-info-box" style="width:21%;">
						<a class="font-12" href="javascript:void(0)"><strong>+<?php echo(count($collegesRepresentedTabData)-1) ?> more</strong></a>
					</div>
					<?php } ?>
					<div class="clearfix">
					</div>
					
				</div>
			</li>
			<li onclick="$j('#leftnav-countriesRepresented').trigger('click'); studyAbroadTrackEventByGA('ABROAD_CONSULTANT_PAGE', 'countriesRepresentedTab');" style="cursor:pointer;">
				<div class="college-rep-sec">
					<div class="college-info-box consultant-info-title">
						<strong>Countries</strong>
					</div>
					<div class="college-flag-criteria">
						<ul>
							<?php
							$counter = 1;
							foreach($countriesRepresentedTabData['countriesRepresented'] as $k => $v)
							{?>
							<li>
								<?php if($counter==3)break;?>
								<a href="Javascript:void(0);" class="flLt">
								<i class="flags <?php echo str_replace(" ",'',(strtolower($v))); ?> " title="<?php echo htmlentities($v); ?>"></i></a>
								<span><?php echo htmlentities($v); ?></span>
								<?php $counter++; ?>
							</li>
							<?php } ?>
						</ul>
					</div>
					<?php
					if(count($countriesRepresentedTabData['countriesRepresented'])>2)
					{ ?>
					<div class="college-info-box" style="width:21%">
						<a href="javascript:void(0)" class="font-12">
							<strong>+<?php echo (count($countriesRepresentedTabData['countriesRepresented'])-2); ?> more</strong>
						</a>
					</div>
					<?php } ?>
					<div class="clearfix"></div>
				</div>
			</li>
			<?php }
			
			if($studentAdmittedTabFlag==1)
			{ ?>
			<li onclick="$j('#leftnav-studentAdmitted').trigger('click'); studyAbroadTrackEventByGA('ABROAD_CONSULTANT_PAGE', 'studentAdmittedTab');" style="cursor:pointer;">
				 <div class="college-rep-sec" id ="studentAdmittedLink" style="padding:0;">
					<div class="college-info-box consultant-info-title">
						<strong>Students sent</strong>
					</div>
				    <div class="clearwidth">
					 <?php
					$studentprofiles = $consultantObj->getConsultantStudentProfiles();
					$count = 1;
					$studentTuple = reset($studentprofiles);
					$examMapping = $studentTuple->getProfileExamMapping();
					$universityMappingData = $studentTuple->getProfileUniversityMapping();          
					?>
						<div class="student-info-col">
							<a href="javascript:void(0);" class ="StudentAdmittedProfileLink" elementtofocus="studentAdmitted-tab" onclick="resetStudentSlider(this,'<?php echo $count ?>');">
								<strong><?php echo htmlentities(formatArticleTitle($studentTuple->getStudentName(),35));?></strong>
							</a>
							<p>
							    <?php echo htmlentities(formatArticleTitle($universityMappingData[0]['courseName'],40));?>,<br>
							    <?php echo formatArticleTitle($studentAdmittedMappingUniversityData[$universityMappingData[0]['universityId']]['universityName'],40);?> 
							</p>
							<?php if(!in_array($examMapping[0]['examId'] ,array(9999,9998))){ ?>
							<p>
							    <?= $examMapping[0]['examName'];?><?= ($examMapping[0]['examScore']!='')?": ".htmlentities($examMapping[0]['examScore']):""?><?= (count($examMapping)>1)?"  , ".$examMapping[1]['examName']:""?><?= ($examMapping[1]['examScore']!='')?": ".htmlentities($examMapping[1]['examScore']):""?>
							</p>
							<?php } ?>
						</div>
				    </div>
				    <?php if(count($studentprofiles)>1){
					?>
					<div class="college-info-box" style="width:21%;">
						<a href="javascript:void(0);" class="font-12" style="margin-top:15px;"><strong>+<?php echo(count($studentprofiles)-1); ?> more</strong></a>
					</div>
				   <?php }?>
					<div class="clearfix"></div>
				  </div>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>