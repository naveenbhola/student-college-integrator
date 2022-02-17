	<?php
	$bannerProperties = array('pageId'=>'CAREER_CENTRAL_SUGGESTION', 'pageZone'=>'HEADER');
	$headerComponents = array(
	   'js' 	=>	array('common'),
	   'callShiksha'=>1,
	   'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
		'title'=>'Career Counselling &amp; Development after 12th - Shiksha.com',
   		'metaDescription'=>'Find career counselling and tips for your career planning at Shiksha.com. It provides relevant career search based on your stream and interest.',
	   'notShowSearch' => true,
	   'showBottomMargin' => false,
	   'showApplicationFormHeader' => false,
	   'product'=>'CareerProduct',
	   'bannerProperties' => $bannerProperties,
	   'canonicalURL' => $current_page_url
	);
	$this->load->view('common/header', $headerComponents);?>
		<div class="career-wrapper">
			<div class="career-child-content">
			  <?php
                $this->load->view('Careers/careerPageBreadcrumb',array('careerPage'=>'Counselling'));
                ?>

			<?php if(!empty($result)){
				?>
				<?php
			$totalNumberOfSuggestions = count($result);
			?>
			<div class="suggestion-header">
			<?php if($totalNumberOfSuggestions>1){$career = ' careers ';}else{$career = ' career ';}?>
			    <h1>Hi, We found <?php echo $totalNumberOfSuggestions;?><?php echo $career;?>relevant to you based on your stream & interest</h1>
			    <div class="sorting-block">
			    <ul class="suggestion-sorting">
				<li>Your Stream : </li>
				<li><a href="javascript:void(0);" class="active"><?php echo preg_replace('/Humanities/','Humanities/Arts',$stream);?></a></li>
				<li><a href="<?php echo CAREER_HOME_PAGE;?>">Change</a></li>
			    </ul>
			    <span style="color: #bcbcbc; float: left; display: block; margin: 0px 25px 0; font-size: 16px">|</span>
			    <ul class="suggestion-sorting">
				<li>Your Career Interest:</li>
				<?php if(!empty($expressInterestFirstName)){ ?><li><a href="javascript:void(0);" class="active"><?php echo $expressInterestFirstName;?></a></li><?php } ?>
				<?php if(!empty($expressInterestSecondName)){ ?><li><a href="javascript:void(0);" class="active"><?php echo $expressInterestSecondName;?></a></li><?php } ?>
				<li><a href="<?php echo CAREER_EXPRESSINTEREST_PAGE; ?>">Change</a></li>
			    </ul>
			    <div class="clearFix"></div>
			    </div>
			</div>
			
			<div class="suggestion-result">
			    <ul>
				<?php
				foreach($result as $key=>$value){
				$res = $value->getOtherCareerInformation();
				$streamAndExpressInterest = $res['streamAndExpressInterest'];
				$streamAndExpressInterestArray = explode('#',$streamAndExpressInterest);
				$expressInterest = $streamAndExpressInterestArray[1];
				$expressInterestArray = explode(',',$expressInterest);
				if(!empty(json_decode($_COOKIE['expressInterestDetail'])->ei1) && !empty(json_decode($_COOKIE['expressInterestDetail'])->ei2)){
					if(count($expressInterestArray)==1){
						if($expressInterestArray[0]==$expressInterestFirstName){
							$expressInterest = $expressInterestFirstName.', <b style="text-decoration: line-through;font-weight:normal;">'.$expressInterestSecondName.'</b>';		
						}
						
						if($expressInterestArray[0]==$expressInterestSecondName){
							$expressInterest = '<b style="text-decoration: line-through;font-weight:normal;">'.$expressInterestFirstName.'</b>,'.$expressInterestSecondName;		
						}
					
					}
				}
				
				$stream = $streamAndExpressInterestArray[0];
				?>
				<li>
					<div class="figure"><img src="<?php echo MEDIA_SERVER.$value->getImage();?>" alt="Career as <?php echo htmlentities($value->getName());?>" height="175" width="120"/></div>
				    <div class="details">
					<h2><a href="<?php echo SHIKSHA_HOME.$value->getCareerUrl();?>" title="<?php echo htmlentities($value->getName());?>"><?php echo htmlentities($value->getName());?></a></h2>
					<p><?php echo $value->getShortDescription();?></p>
					<div class="info-block">
						<div class="proff-details" style="width:480px;">
						<?php
						$mandatorySubject = $value->getMandatorySubject();
						$minimumSalaryInLacs = $value->getMinimumSalaryInLacs();
						$maximumSalaryInLacs = $value->getMaximumSalaryInLacs();
						$minimumSalaryInThousand = $value->getMinimumSalaryInThousand();
						$maximumSalaryInThousand = $value->getMaximumSalaryInThousand();
						$difficultyLevel = $value->getDifficultyLevel();
						if($minimumSalaryInLacs>1){$minLakh = ' Lakhs ';}else{$minLakh = ' Lakh ';}
						if($maximumSalaryInLacs>1){$maxLakh = ' Lakhs ';}else{$maxLakh = ' Lakh ';}
						if(!empty($minimumSalaryInLacs)){$minSalToDisplay=$minimumSalaryInLacs+($minimumSalaryInThousand/100).$minLakh; }else{$minSalToDisplay=$minimumSalaryInThousand.' Thousand ';}
						if(!empty($maximumSalaryInLacs)){$maxSalToDisplay=$maximumSalaryInLacs+($maximumSalaryInThousand/100).$maxLakh; }else{$maxSalToDisplay=$maximumSalaryInThousand.' Thousand ';}
						?>
						
						<?php if(!empty($minimumSalaryInLacs) || !empty($maximumSalaryInLacs) || !empty($minimumSalaryInThousand) || !empty($maximumSalaryInThousand)){ ?>
						
						<div class="prof-sal"><span>Salary:</span> 
						<?php if((!empty($minimumSalaryInLacs) || !empty($minimumSalaryInThousand)) && (!empty($maximumSalaryInLacs) || !empty($maximumSalaryInThousand))){
						echo $minSalToDisplay;?> to <?php echo $maxSalToDisplay;?>
						<?php } ?>

						<?php if((empty($minimumSalaryInLacs) && empty($minimumSalaryInThousand)) && (!empty($maximumSalaryInLacs) || !empty($maximumSalaryInThousand))){
						?> Up to <?php echo $maxSalToDisplay;?>
						<?php } ?>

						<?php if((!empty($minimumSalaryInLacs) || !empty($minimumSalaryInThousand)) && (empty($maximumSalaryInLacs) && empty($maximumSalaryInThousand))){
						?> Starts from <?php echo $minSalToDisplay;?>
						<?php } ?>  per annum</div>
						<?php } ?>
						<div class="prof-subject">
						<?php
							$countOfStream = count(explode(',',$stream));
							if($countOfStream>1){
								$streamHeading = 'Std XII Streams:';
							}else{
								$streamHeading = 'Std XII Stream:';
							}
							$countOfSubject = count(explode(',',$mandatorySubject));
							if($countOfSubject>1){
								$subjectHeading = 'Mandatory Subjects:';
							}else{
								$subjectHeading = 'Mandatory Subject:';
							}
						?>
						<?php if(!empty($stream)){ ?><span>Std XII Stream:</span> <?php echo preg_replace('/Humanities$/','Humanities/Arts',$stream); } ?>
						<?php if(!empty($mandatorySubject)){
							if(!empty($stream)){?> <br/><?php }?>
						<span><?php echo $subjectHeading;?></span> <?php echo str_replace(',',', ',$mandatorySubject);?><?php } ?>
						
						</div>
						<?php if(!empty($expressInterest)){ ?><div class="career-int"><span>Career Interest:</span> <?php echo $expressInterest;?></div><?php } ?>
						<?php if(!empty($difficultyLevel)){ ?>
					    <div class="academic-difficulty">
					    <i class="academic-deff-icon"></i>
					    <span>Academic Difficulty:</span>
					    <?php echo $difficultyLevel;?>
					    </div>
					    <?php } ?>
						<div class="spacer8 clearFix"></div>
						<div class="flLt"><input type="button" value="More about <?php echo htmlentities($value->getName());?>" class="orange-button" onClick="window.location.href = '<?php echo SHIKSHA_HOME.$value->getCareerUrl();?>';"/></div>
					    </div>

					    <?php if(!empty($featuredColleges[$value->getCareerId()])) {$this->load->view('Careers/featuredColleges',array("featuredColleges" => $featuredColleges[$value->getCareerId()] , "type" => 1 ));} ?>
					    
					</div>
				    </div>
				</li>
				<?php } ?>
			    </ul>
			</div>
			<div class="more-recom">Not happy with the recommended careers? <a href="<?php echo CAREER_EXPRESSINTEREST_PAGE;?>">&laquo; Go back & change your criteria.</a></div>
			<?php }else{ ?>
				<div class="no-result-found">
					<span class="no-result-icn"></span>
					<h5>Sorry, no careers match your interest criteria.<br />
					<span>Please <a href="<?php echo CAREER_EXPRESSINTEREST_PAGE;?>">go back</a> & try another criteria.</span>
					</h5>
				</div>
			<?php } ?>
		    </div>
		</div>
		<!--Code Ends here-->

	    <?php
		$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
		$this->load->view('common/footer',$bannerProperties1);
	    ?> 
<script>
function displayToolTip(style,divId) {
        if($(divId)) {
                $(divId).style.display = style;
        }
}
</script>
