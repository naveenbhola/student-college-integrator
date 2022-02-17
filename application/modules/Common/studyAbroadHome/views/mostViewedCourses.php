<?php
	$this->load->library('listing/AbroadListingCommonLib');
	$this->abroadListingCommonLib = new AbroadListingCommonLib();
?>
<div class="carausel-section clearfix">		
    <div class="arrow-box">
		<a href="javascript:void(0)" class="home-sprite prev-scroll" title="Previous" onclick="enterpriseSlideLeftC();" id="prevButtonC"></a>
	</div>	
	<div class="carausel-content" style="overflow: hidden;">
		<div class="clearfix">
			<i class="home-sprite viewed-icon flLt"></i><h2 class="flLt">Most viewed Courses</h2>
		</div>
		<ul style="width:2398px; position: relative; left:0px;" id="slideContainerC">
			<li id="slideC1" style="float: left; width:798px;">
				<?php if(is_array($courses) && count($courses)>0){ ?>
					<?php for ($i=0;$i<min(8,count($courses));$i++){ 
						$imgURL = $courses[$i]['logoImage'];
	                    if($i > 3) {
	                      $lazyClass = 'class="lazyImg"';
	                      $dataSrc = 'data-original="'.$imgURL.'"';
	                    }else{
	                      $lazyClass = 'class="lazy"';
	                      $dataSrc = 'data-original="'.$imgURL.'"';
	                    }
						?>
						<div class="carausel-details" style="border: none;">
							<div style="" onClick="studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'MostViewed' , '<?=$courses[$i]['countryName']?>'); window.location='<?php echo $courses[$i]['courseObj']->getURL(); ?>';">
							    <div class="viewed-tupple-title">
									<h3>In <?=$courses[$i]['countryName']?></h3>
									<p class="uni-name">
										<a href="<?=$courses[$i]['universityURL']?>" title="<?=htmlentities($courses[$i]['courseObj']->getUniversityName())?>">
											<?php echo cutString(htmlentities($courses[$i]['courseObj']->getUniversityName()),20); ?>
										</a>
										<br/>
										<?=$courses[$i]['courseObj']->getMainLocation()->getCity()->getName()?>, <?=$courses[$i]['courseObj']->getMainLocation()->getCountry()->getName()?>
									</p>
									<i class="home-sprite pointer"></i>
							    </div>
							    <div class="university-thumb">
									<a href="<?=$courses[$i]['courseObj']->getURL()?>">
										<img alt="<?=$courses[$i]['courseObj']->getName()?>" title="<?=$courses[$i]['courseObj']->getName()?>" <?php echo $lazyClass.' '.$dataSrc; ?> />
									</a>
							    </div>
							    <div class="viewed-course-box clearfix">
									<div class="viewed-child">
										<p style="height:24px;">
											<a href="<?=$courses[$i]['courseObj']->getURL()?>" style="font-weight:bold" title="<?=htmlentities($courses[$i]['courseObj']->getName())?>">
												<?php echo formatArticleTitle(htmlentities($courses[$i]['courseObj']->getName()),30); ?>
											</a>
										</p>
										<div class="viewed-course-details">
											<?php
												$fees = $courses[$i]['courseObj']->getTotalFees()->getValue();
												if($fees){
													$feesCurrency = $courses[$i]['courseObj']->getTotalFees()->getCurrency();
													$courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
													$courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
													$courseFees = str_replace("Lac","Lakh",$courseFees);
												}
											?>
											<p>
												Course Duration
												<strong><?=$courses[$i]['courseObj']->getDuration()->getDisplayValue()?></strong>
											</p>
											<p>
												1st Year Total Fees:
												<strong><?=$courseFees?></strong>
											</p>
											<p>
												Eligibility:
												<strong>
													<?php
														$examCount = 0;
														foreach($courses[$i]['courseObj']->getEligibilityExams() as $examObj){
															if($examObj->getId() == -1){continue;}
															if(++$examCount >= 3){continue;}
															if($examCount==2){
																echo ", ";
															}
															if($examObj->getCutoff() == "N/A"){
																echo "<span onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)' style='position:relative'>";
																$this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName(),'shortVersion'=>true));
															}
															echo htmlentities($examObj->getName()).(($examObj->getCutoff()=="N/A")?"":": ".$examObj->getCutoff());
															if($examObj->getCutoff() == "N/A"){
																echo "</span>";
															}
														}
													?>
												</strong>
											</p>
										</div>
									</div>
									<a href="<?=$courses[$i]['countryPageURL']?>">View popular universities in <?=$courses[$i]['countryName']?> &gt;</a>
								</div>
							</div>
						</div>
						<?php if($i == 3){?>
							</li>
							<li id="slideC2" style="float: left; width:798px;">
						<?php }else{ ?>
						<?php } ?>
					<?php }	?>
				<?php } ?>
			</li>
			<div class="clearFix"></div>
		</ul>
		<div class="clearwidth">
			<ol class="carausel-bullets">
				<li id="slidecontrolC1" class="active" style="cursor:pointer;" onclick="enterpriseSlideC(1);"></li>
				<li id="slidecontrolC2" style="cursor:pointer;" onclick="enterpriseSlideC(2);"></li>
			</ol>
		</div>
	</div>
	<div class="arrow-box">
		<a href="javascript:void(0)" class="home-sprite next-scroll-active" title="Next" onclick="enterpriseSlideRightC();" id="nextButtonC"></a>
	</div>
</div>
<script>
	var slideWidthC = 798;
	var numSlidesC = 2;
	var currentSlideC = 0;
	var intervalPeriodC = 5000;
	var intervalC;
</script>
