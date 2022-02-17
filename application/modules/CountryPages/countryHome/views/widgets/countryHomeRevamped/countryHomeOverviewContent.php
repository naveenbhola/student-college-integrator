<div class="n-div clearwidth">   
	<!--studnets visa for reaching usa-->
	<?php
		//_p($countryOverview);
		$visa = $countryOverview['visa'];
		$work = $countryOverview['work'];
		$economy = $countryOverview['economy'];
	?>
	<?php if($visa != false){?>
	<div class="visa-for-reaching clearwidth">
		<h2 class="titl">Students Visa for reaching <?php echo ucwords($countryObj->getName()); ?></h2>
		<div class="steps-to-visa">
			<?php if($visa['complexity'] != ""){ ?>
				<div class="first-step">
					<h2 class="main-titl">Visa application process</h2>
					<div class="visa-processSec">
                    	<div class="process-bar">
                        	<span class="bar-circle <?php if($visa['complexity'] == "simple") echo "active";?>"><strong style="left:-10px;">Simple</strong></span>
                            <span style="left:46%;" class="bar-circle <?php if($visa['complexity'] == "moderate") echo "active";?>"><strong>Moderate</strong></span>
                            <span style="right:0; left:auto;" class="bar-circle <?php if($visa['complexity'] == "complex") echo "active";?>"><strong>Complex</strong></span>
                        </div>
                    </div>
				</div>
			<?php } ?>  
			<?php if($visa['fees']){ ?>
				<div class="second-step">
					<h2 class="main-titl">Fees for visa</h2>
					<p class="visa-fee"><?php echo $visa['fees']; ?></p>
				</div>
			<?php } ?>
			<?php if($visa['timeline']){ ?> 
				<div class="third-step">
					<h2 class="main-titl">Timelines and visa processing</h2>
					<p class="visa-fee"><?php echo htmlentities($visa['timeline']); ?></p>
				</div>
			<?php } ?>
			<div class="clearfix"></div>
		</div>
		<div class="inf-block">
			<?php if($visa['description']){ ?>
				<p class="inf-p"><?php echo formatArticleTitle((strip_tags($visa['description'])),500); ?></p>
			<?php } ?>
			<a class="read-more" href="<?php echo $visa['link']; ?>" target="_blank">Read more about visa in <?php echo ucwords($countryObj->getName()); ?>&gt;</a>
		</div>
	</div>
	<?php } ?>
	<?php if($work){?>
		<div class="visa-for-reaching clearwidth">
			<h2 class="titl">Study and work in <?php echo ucwords($countryObj->getName()); ?></h2>
			<div class="steps-to-work clearwidth">
				<?php if($work['preLink']){ ?>
					<div class="job-step">
						<p class="part-time">Part time jobs in <?php echo ucwords($countryObj->getName()); ?></p>
						<div class="job-dtls">
							<div class="job-col">
								<?php if(empty($work['prestatus']) || $work['prestatus'] == "not permitted"){ ?>
									<p class="job-hrs">Not Permitted</p>
									<span class="week-count">&nbsp;</span>
								<?php }else{ ?>
								<p class="job-hrs"><?php echo $work['prehours'];?></p>
								<span class="week-count"><?php echo $work['predays'];?></span>
								<?php } ?>
							</div>
							<div class="job-analysis">
								<?php if($work['preDescription']){?><p><?php echo formatArticleTitle((strip_tags($work['preDescription'])),120);?></p><?php } ?>
								<a class="read-more" href="<?php echo $work['preLink'];?>" target="_blank">Read more &gt;</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if($work['postLink']){?>
					<div class="job-step">
						<p class="part-time">Work permit after study in <?php echo ucwords($countryObj->getName()); ?></p>
						<div class="job-dtls">
							<div class="job-col">
								<?php if($work['poststatus'] == "not permitted"){?>
									<p class="job-hrs">Not Permitted</p>
									<span class="week-count">&nbsp;</span>
								<?php }else{ ?>
									<p class="job-hrs"><?php echo $work['posthours'];?></p>
									<span class="week-count"><?php echo $work['postdays'];?></span>
								<?php } ?>
							</div>
							<div class="job-analysis">
								<?php if($work['postDescription']){?><p><?php echo formatArticleTitle((strip_tags($work['postDescription'])),120);?></p><?php } ?>
								<a class="read-more" href="<?php echo $work['postLink'];?>" target="_blank">Read more &gt;</a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php if($economy){	?>
		<div class="visa-for-reaching clearwidth">
			<h2 class="titl">Economic overview of <?php echo ucwords($countryObj->getName()); ?></h2>
			<div class="steps-to-work">
				<?php if($economy['growthLink']){ ?>
					<div class="job-step">
						<p class="part-time">Economic growth rate</p>
						<div class="job-dtls">
							<div class="job-col">
								<p class="job-hrs"><?php echo htmlentities(strip_tags($economy['percentage']));?></p>
								<span class="week-count">growth rate</span>
							</div>
							<div class="job-analysis">
								<p><?php echo formatArticleTitle((strip_tags($economy['growthDescription'])),120);?></p>
								<a class="read-more" href="<?php echo $economy['growthLink'];?>" target="_blank">Read more &gt;</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if($economy['sectorLink']){ ?>
					<div class="job-step">
						<p class="part-time">Popular job sectors</p>
						<div class="job-dtls">
							<div class="job-sector">
								<p><?php echo formatArticleTitle((strip_tags($economy['sectorDescription'])),120);?></p>
								<a class="read-more" href="<?php echo $economy['sectorLink'];?>" target="_blank">Read more &gt;</a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>