<?php
	$counter = 0;
	foreach ($branchInformation as $row) {
		$counter += 1;
		$courseId = $row->getShikshaCourseId();
		?>
		<div class="cutOff-tuple-cont">
			<div class="cutOff-tuple">
				<div class="cutOff-row">
					<div class="cutOff-Lft">
						<div class="cutOff-insImg">
							<img src="<?php echo $courseData[$courseId]['thumbUrl']; ?>"/>
						</div>
						<div class="cutOff-insNameDet">
							
							<a href="<?php echo $courseData[$courseId]['instituteUrl']; ?>"><?php echo strlen($courseData[$courseId]['instituteName']) > 75 ? substr($courseData[$courseId]['instituteName'], 0,72).'...' : $courseData[$courseId]['instituteName']; ?></a>
							<span class="cutOff-loc"><?php echo !empty($courseData[$courseId]['localityName']) ? $courseData[$courseId]['localityName'].", ":""; ?> <?php echo $courseData[$courseId]['cityName']; ?></span>
							<a href="<?php echo $courseData[$courseId]['courseUrl']; ?>"><?php echo strlen($row->getBranchName()) > 75 ? substr($row->getBranchName(), 0, 72).'...' : $row->getBranchName(); ?></a>
						</div>
					</div>
					<div class="cutOff-Rgt">
						<ul class="cutOff-Rounds">
							<?php 
							$roundInfo = $row->getRoundsInfo();
							foreach ($roundInfo as $roundData) {
								?>
								<li>
									<label>Round <?php echo $roundData['round']; ?></label>
									<span><?php echo empty($roundData['closingRank']) ? '-' : $roundData['closingRank'].'%'; ?></span>
								</li>
								<?php
							}
							?>
						</ul>
						<div class="CutOffbtn-sec">
							<a href="javascript:void(0);" id='shortlist_<?php echo $courseId; ?>' shortlistCourseId="<?php echo $courseId; ?>" trackingKeyId="<?php echo $shortlistTrackingKeyId; ?>" class="btn-star shortlist <?php echo isset($shortlistedCoursesOfUser[$courseId]) ? 'shortlisted' : ''; ?>"></a>
							<a href="javascript:void(0);" class="btn-blue addToCmp" id='compare_<?php echo $courseId; ?>' compareCourseId="<?php echo $courseId; ?>" trackingKeyId="<?php echo $compareTrackingKeyId; ?>" ><span>Add To Compare</span></a>
							<a href="javascript:void(0);" class="btn-org <?php echo (isset($_COOKIE['applied_'.$courseId]) && $_COOKIE['applied_'.$courseId] == 1)? "disable-btn":"dnldBrchr";?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" hideRecoLayer=1 <?php echo ($validateuser === 'false')  ? "reloadAfter=1":""; ?> courseName="<?php echo $courseData[$courseId]['courseName']; ?>" clientCourseId="<?php echo $courseId; ?>" trackingKeyId="<?=$brochureTrackingKeyId;?>">Apply Now</a>
						</div>
					</div>
				</div>
			</div>
			<?php 
			$remarks = $row->getRemarks();
			if(!empty($remarks)){
				?>
				<p class="cutOff-txt"><?php echo $remarks; ?></p>
				<?php
			}
			if($counter == 3 ){
				$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1_desktop','bannerType'=>"content"));
			}
			if($counter == 7 ){
				$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2_desktop','bannerType'=>"content"));
			}
			if($counter == 10 ){
				$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C3_desktop','bannerType'=>"content"));
			}
			if($counter == 14 ){
				$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C4_desktop','bannerType'=>"content"));
			}
			?>
		</div>
		<?php
	}
?>