<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,700,800,400italic,400,600,600italic' rel='stylesheet' type='text/css'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cover Page</title>
<link rel="stylesheet" type="text/css" href="https://<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('ebrochureAbroad'); ?>" />
</head>
<body>
	<?php
		$inrSymbolImage = "<img src = '".SHIKSHA_HOME."/public/images/rupee.png' style='margin-top:10px'/>";  // This is the image being used for INR all over the brochure	
	?>
    <div class="page-wrapper">
    	<table cellpadding="0" cellspacing="0" class="grid-table">
        	<!--tr>
            	<td style="background-color:#a0779f">&nbsp;</td>
                <td style="background-color:#ae9bae">&nbsp;</td>
                <td style="background-color:#b2b0bd">&nbsp;</td>
                <td style="background-color:#b4aec6">&nbsp;</td>
                <td style="background-color:#bcbbb7">&nbsp;</td>
                <td style="background-color:#c0cda1">&nbsp;</td>
                <td style="background-color:#e8bd55">&nbsp;</td>
                <td style="background-color:#fab746">&nbsp;</td>
            </tr-->
            <tr>
            	<td style="background-color:#cf7b8a">&nbsp;</td>
                <td style="background-color:#f8a88f">&nbsp;</td>
                <td style="background-color:#e0b19f">&nbsp;</td>
                <td style="background-color:#deb0b0">&nbsp;</td>
                <td style="background-color:#f3c9cd">&nbsp;</td>
                <td style="background-color:#bacac0">&nbsp;</td>
                <td style="background-color:#d0cc75">&nbsp;</td>
                <td style="background-color:#ecc65b">&nbsp;</td>
            </tr>
             <tr>
            	<td style="background-color:#f48188">&nbsp;</td>
                <td style="background-color:#fdc8c2">&nbsp;</td>
                <td style="background-color:#ffdabd">&nbsp;</td>
                <td style="background-color:#ffe8d0">&nbsp;</td>
                <td style="background-color:#f7efed">&nbsp;</td>
                <td style="background-color:#e8f3ef">&nbsp;</td>
                <td style="background-color:#d5f1e3">&nbsp;</td>
                <td style="background-color:#d7c873">&nbsp;</td>
            </tr>
            <tr>
            	<td style="background-color:#fe8c96">&nbsp;</td>
                <td style="background-color:#f3cad0">&nbsp;</td>
                <td colspan="4" rowspan="5" valign="top">
                	<table width="100%" class="cover-page-content"  border="0" cellspacing="0" cellpadding="0" bgcolor="#000000">
                  <tr>
                    <td bgcolor="#FFFFFF" valign="middle">
                    <div style="padding:0px 0 0 25px">
                    <img src="<?=$university->getLogoLink()?>" />
                    </div>
                    </td>
                    
                  </tr>
                  <tr>
                    <td class="course-title">
                    	<div style="color:#FFF; text-transform:uppercase; display:table; width:80%; height:371px; margin:0 auto">
                        	<div style="display:table-row">
                        	<div class="course-name"><?=$course->getName()?></div>						</div>
                            <div style="display:table-row">
                            <div class="university-add">
			    <?=$university->getName()?>, <?php echo $university->getLocation()->getCity()->getName().
			    (!in_array($university->getLocation()->getState()->getId(), array(0, -1))  ? ", ".$university->getLocation()->getState()->getName() : "")
			    ." | ".$university->getLocation()->getCountry()->getName(); ?></div>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                </td>
                <td style="background-color:#d1e7db">&nbsp;</td>
                <td style="background-color:#bbb993">&nbsp;</td>
            </tr>
            <tr>
            	<td style="background-color:#f89ba6">&nbsp;</td>
                <td style="background-color:#e6c4d2">&nbsp;</td>
                <td style="background-color:#dfead9">&nbsp;</td>
                <td style="background-color:#95b8be">&nbsp;</td>
            </tr>
            <tr>
            	<td style="background-color:#e9a7b3">&nbsp;</td>
                <td style="background-color:#e1c1ce">&nbsp;</td>
                <td style="background-color:#c3d8c9">&nbsp;</td>
                <td style="background-color:#a6c6b9">&nbsp;</td>
            </tr>
            <tr>
            	<td style="background-color:#d6acba">&nbsp;</td>
                <td style="background-color:#ebcac3">&nbsp;</td>
                <td style="background-color:#ebc8a8">&nbsp;</td>
                <td style="background-color:#c4c8a7">&nbsp;</td>
            </tr>
            <tr>
            	<td style="background-color:#f5cb99">&nbsp;</td>
                <td style="background-color:#f9d7b2">&nbsp;</td>
                <td style="background-color:#ebbea7">&nbsp;</td>
                <td style="background-color:#dab996">&nbsp;</td>
            </tr>
            <!--<tr>
            	<td style="background-color:#fac87f">&nbsp;</td>
                <td style="background-color:#fcda9d">&nbsp;</td>
                <td style="background-color:#fff8ce">&nbsp;</td>
                <td style="background-color:#fef1bc">&nbsp;</td>
                <td style="background-color:#fef5cc">&nbsp;</td>
                <td style="background-color:#f8e9ca">&nbsp;</td>
                <td style="background-color:#d2d2b8">&nbsp;</td>
                <td style="background-color:#cfc6a7">&nbsp;</td>
            </tr>-->
            <tr>
            	<td style="background-color:#e9b77a">&nbsp;</td>
                <td style="background-color:#f5e2a8">&nbsp;</td>
                <td style="background-color:#e1c59e">&nbsp;</td>
                <td style="background-color:#fee69e">&nbsp;</td>
                <td style="background-color:#ebdba8">&nbsp;</td>
                <td style="background-color:#ebdba8">&nbsp;</td>
                <td style="background-color:#bdd6c1">&nbsp;</td>
                <td style="background-color:#c4d7b7">&nbsp;</td>
            </tr>
            <tr>
            	<td colspan="8" style="background-color:#fff; height:99px" align="center">
                	<span style="font-size:11pt; font-style:italic; font-weight: 400">Exclusive Guide by: </span><img style="vertical-align:middle; margin-left:20px" src="<?=SHIKSHA_HOME?>/public/images/shiksha-logo-pdf.gif" />
                </td>
            </tr>
            <tr>
            	<td style="background-color:#9c7ea4">&nbsp;</td>
                <td style="background-color:#b261a6">&nbsp;</td>
                <td style="background-color:#d86e94">&nbsp;</td>
                <td style="background-color:#e9ab70">&nbsp;</td>
                <td style="background-color:#d6bd7a">&nbsp;</td>
                <td style="background-color:#c5ba8d">&nbsp;</td>
                <td style="background-color:#b2abb3">&nbsp;</td>
                <td style="background-color:#92c6de">&nbsp;</td>
            </tr>
        </table><!-- FIRST PAGE ENDS -->
		
		<div class="content-wrap">
            <div class="course-title-box">
                <h1><?=$course->getName()?></h1>
            </div>
            
            <div class="note-box clearfix">
                <div class="left-col course-left">
                	<i>Course Duration:<br />
					<span><?=$course->getDuration()->getExactDurationValue()." ".$course->getDuration()->getDurationUnit()?></span>
                    </i>
                </div>
                <div class="right-col course-right">
					<?php
					if(strpos($course->getInstituteName(),"_DUMMYDEPARTMENT")==FALSE){
					?>
						<i>Offered by:<br />
						<span><?=$course->getInstituteName()?></span>
						</i>
					<?php
					}
					?>
                </div>
             </div>

            
            <div class="content-child">
            	<div class="content-details">
                    <h2>Course Description</h2>
                    <div class="wiki-content">
                        <?=str_replace("/mediadata/images/",SHIKSHA_HOME."/mediadata/images/",$course->getCourseDescription())?>
                    </div>
                </div> 
                <div class="content-details" >
                	<div class="icon-col">
                			<img width="69" height="68" alt="info-box" src="<?=SHIKSHA_HOME?>/public/images/info-icon.jpg">
		        </div>
                    <div class="detail-col">
						<h2>
							<?php
							echo "About ".$university->getName().", ";
							if(reset($university->getLocations())->getCity()->getName()){
								$location = reset($university->getLocations())->getCity()->getName();
							}
							if(reset($university->getLocations())->getState()->getName())
							{
								  $location .= ", ".reset($university->getLocations())->getState()->getName();
							}
							echo $location ." | ".reset($university->getLocations())->getCountry()->getName();
							?>
						</h2>
						<?php
							$university_type = '';
							if($university->getTypeOfInstitute()=='not_for_profit')
							{
								$university_type .= "Not For Profit"." ".ucfirst($university->getTypeOfInstitute2());
							}
							else {
								$university_type .= ucfirst($university->getTypeOfInstitute())." ".ucfirst($university->getTypeOfInstitute2());
							}
							if($university->getEstablishedYear())
							{
								$university_type .= ", Estd in ".$university->getEstablishedYear();
							}
							if($university_type != ''){
						?>	    
								<p><?=$university_type?></p>
						<?php
							}
						?>
						<p><?php if($university->getAffiliation()){echo "Affilation: ".$university->getAffiliation();}?></p>
						<p><?php if($university->getAccreditation()){echo "Accreditation: ".$university->getAccreditation();}?></p>
						<div class="wiki-content" style="margin-top:20px">
							<h2>University Highlights</h2>
							<ul>
								<?=str_replace("/mediadata/images/",SHIKSHA_HOME."/mediadata/images/",$university->getWhyJoin())?>
							</ul>
						</div>
					</div>
				</div>
			</div><!-- 2nd page ends here-->
			<div class="content-child">
            	<div class="content-details clearfix">
                    <h2>Course Fee</h2>
                    <p>1st Year Tuition Fee For This Course</p>
                    <table width="100%">
						<tr>
							<td>
								<table class="course-fee-table" style="width:350px">
									<tr>
										<?php
											$temp = $courseFeeData["fromCurrenyObj"]->getCode();
											if(!empty($currencySymbolMapping[$courseFeeData['fromCurrency']]) || !empty($temp)){
										?>
										<td width="68">
											<?php
												if(!empty($currencySymbolMapping[$courseFeeData['fromCurrency']])) {
											?>	
													<div class="currency-box"><?=($courseFeeData['fromCurrency']==1)?$inrSymbolImage:$currencySymbolMapping[$courseFeeData['fromCurrency']]?></div>
											<?php
												}
												else {
													$currencySize = 44 - 5*strlen($courseFeeData["fromCurrenyObj"]->getCode());
											?>
													<div class="currency-box" style="font-size: <?=$currencySize?>px !important"><b><?=$courseFeeData["fromCurrenyObj"]->getCode()?></b></div>
											<?php
												}
											?>
										</td>
										<?php
											}	
										?>
										<td width="100" valign="bottom">
											<strong style="font-family:OpenSans-Bold">
												<?=$courseFeeData['fromFormattedFees']?>
											</strong>
											<br />Annually
										</td>
										<?php
											if($courseFeeData['fromCurrency']!=1){
										?>
												<td width="20" style="padding-right:30px"><strong>=</strong></td>
												<td width="68">
													<div class="currency-box"><?=$inrSymbolImage?></div>
												</td>
												<td width="100" valign="bottom">
													<strong style="font-family:OpenSans-Bold">
														<?=$courseFeeData["toFormattedFees"]?>
													</strong>
													<br />Annually
												</td>
										<?php
											}else{
										?>
												<td width="20" style="padding-right:30px"></td>
												<td width="68">	</td>
												<td width="100" valign="bottom"></td>
										<?php
											}
										?>
									</tr>
								</table>    
							</td>
							<td>
								<table style="width:300px;">
									<div style="width:350px; background:#e1d697; position:relative; padding:10px;">
										<p>
											<strong>
												Included:
											</strong>
											Tuition Fees
										</p>
										<p>
											<strong>
												Not-Included:
											</strong>
											Total course fees can include other components which are not included above.
										</p>
										<div class="pointer"></div>
									</div>            	
								</table>
							</td>
						</tr>
                    </table>
                </div>
				<?php
					$eligibilityExams = $course->getEligibilityExams();
					$finalExamList = array();
					foreach($eligibilityExams as $exam){
						if($exam->getId()!=-1){
							$finalExamList[] = $exam;
						}
					}
					$eligibilityExams = $finalExamList;
					if(!empty($eligibilityExams)){
				?>
						<div class="content-details clearfix">
							<table width="100%">
								<tr>
									<td>
										<h2>Eligibility For This Course</h2>
									</td>
									<td align="right">
										<?php if(count($eligibilityExams) > 4) {?><a href="<?=$course->getURL()?>" class="more-link">More >></a> <?php } ?>
									</td>
								</tr>
							</table>
							
							<div class="course-eligibility">
								<ul>
									<li style="margin-bottom:5px">
										<div class="exam-name">&nbsp;</div>
										<div class="cutoff-col" style="border:0 none; font-family:OpenSans-Italic; font-size:10pt"><i>Eligibility Cutoff</i></div>
										<div class="max-col" style="color:#878787; font-size:10pt">Max</div>
									</li>
									<?php
										foreach(array_slice($eligibilityExams,0,4,false) as $exam){
										if(!is_numeric($exam->getMaxScore())){
										    $maxScore = count(explode(",",$exam->getRange())); 
										    $givenCutOff = array_search($exam->getCutOff(),array_reverse(explode(",",$exam->getRange())))+1;
										}
										else{
										    $maxScore = $exam->getMaxScore();
										    $givenCutOff = $exam->getCutoff();
										}
									?>
									<li>
										<div class="exam-name"><?=$exam->getName()?></div>
										<div class="cutoff-col">
											<div class="graph-bar" style="width:<?=($givenCutOff/$maxScore)*100?>%">
												<p><?=$exam->getCutoff()?></p>
											</div>
										</div>
										<div class="max-col">
											<?=$exam->getMaxScore()?>
										</div>
									</li>
									<?php
										}
									?>
								</ul>
							</div>
						</div>
				<?php
					}
				?>
				<?php
					$livingExpense = $university->getCampusAccommodation()->getLivingExpenses();
					if($university->getCampusAccommodation() != '' && $university->getCampusAccommodation()->getCurrencyEntity()!= ''){
						if(!empty($livingExpense)){
					?>
							<div class="content-details clearfix">
								<h2>
									Cost of Living,
									<?php
										$univLocation = reset($university->getLocations());
										$livingCity = $univLocation->getCity()->getName();
										$livingState = $univLocation->getState()->getName();
										$livingCountry = $univLocation->getCountry()->getName();
										$livingLocationString = empty($livingCity)?'':$livingCity.', ';
										$livingLocationString .= empty($livingState)?'':$livingState.', ';
										$livingLocationString .= empty($livingCountry)?'':$livingCountry;
										echo $livingLocationString;
									?>
								</h2>
								<p>Living expenses</p>
								<table>
									<tr>
										<td>
											<table class="course-fee-table" style="width:350px">
												<tr>
													<td width="68">
														<?php
															$livingCurrencyItem = $university->getCampusAccommodation()->getLivingExpenseCurrency()==1?$inrSymbolImage:($currencySymbolMapping[$university->getCampusAccommodation()->getCurrencyEntity()->getId()]==''?$university->getCampusAccommodation()->getCurrencyEntity()->getCode():$currencySymbolMapping[$university->getCampusAccommodation()->getCurrencyEntity()->getId()]);
															$reduction = 5*(strlen($livingCurrencyItem)-1);
															$currencySize = 44;
															if(empty($currencySymbolMapping[$university->getCampusAccommodation()->getCurrencyEntity()->getId()])){
																$currencySize = 44 - $reduction;
															}
															
														?>
														<div class="currency-box" style="font-size: <?=$currencySize?>px !important"><b><?=$livingCurrencyItem?></b></div>
													</td>
													<td width="100" valign="bottom">
														<strong style="font-family:OpenSans-Bold"><?=round($university->getCampusAccommodation()->getLivingExpenses()/12)?></strong> <br />Monthly
													</td>
													<?php
													if($university->getCampusAccommodation()->getLivingExpenseCurrency()!=1){
													?>
														<td width="20" style="padding-right:30px">
															<strong>=</strong>
														</td>
														<td width="68">
															<div class="currency-box"><?=$inrSymbolImage?></div>
														</td>
														<td width="100" valign="bottom">
															<strong style="font-family:OpenSans-Bold">	<?=$livingExpenseINR?></strong>
															<br />Monthly
														</td>
													<?php
													}
													else{?>
														<td width="20" style="padding-right:30px">
														</td>
														<td width="68">
														</td>
														<td width="100" valign="bottom">
														</td>
													<?php
													}
													?>
												</tr>
											</table>  
										</td>
										<td>
											<table width="300">
												<tr>
													<td align="center">
														<?php if(!empty($countryGuideUrl) && strpos($countryGuideUrl,'https://')!==false){ ?>
															<a style="display:block" href = "<?=$countryGuideUrl?>"><img src="<?=$countryGuideImg?>" /></a>
														<?php
														}else{?>
															<img src="<?=$countryGuideImg?>" />
														<?php }?>
													</td>
												</tr>
											</table>  
										</td>
									</tr>
								</table>
					</div>
					<?php
						}
					}
					if($course->getFacultyInfoLink()!='' || $course->getCourseFaqLink()!= '' || $course->getAlumniInfoLink()!='' || $course->getJobProfile()->getCareerServicesLink()!=''){
				?>
						<div class="content-details clearfix">
							<h2>More Info on University Website</h2>
							<table class="info-table">
								<tr>
									<?php
										if($course->getFacultyInfoLink() != ''){
									?>
											<td width="68"><a style="display:block" href="<?=$course->getFacultyInfoLink()?>"><img src="<?=SHIKSHA_HOME?>/public/images/info-icon.jpg" /></a></td>
											<td><a href="<?=$course->getFacultyInfoLink()?>">Faculty<br />Information</a></td>
									<?php
										}
										if($course->getCourseFaqLink()!= ''){
									?>
											<td width="68"><a style="display:block" href="<?=$course->getCourseFaqLink()?>"><img src="<?=SHIKSHA_HOME?>/public/images/ques-icon.jpg" /></a></td>
											<td><a href="<?=$course->getCourseFaqLink()?>">Course<br />FAQs</a></td>
									<?php
										}
										if($course->getAlumniInfoLink()!=''){
									?>
											<td width="68"><a style="display:block" href="<?=$course->getAlumniInfoLink()?>"><img src="<?=SHIKSHA_HOME?>/public/images/hat-icon.jpg" /></a></td>
											<td><a href="<?=$course->getAlumniInfoLink()?>">Alumni<br />Information</a></td>
									<?php
										}
										if($course->getFacultyInfoLink() != '' && $course->getCourseFaqLink()!= '' && $course->getAlumniInfoLink()!=''){
									?>
											</tr>
											<tr>
												<td width="68"><a style="display:block" href="<?=$course->getJobProfile()->getCareerServicesLink()?>"><img src="<?=SHIKSHA_HOME?>/public/images/info-icon.jpg" /></a></td>
												<td><a href="<?=$course->getJobProfile()->getCareerServicesLink()?>">Placement<br />Services</a></td>
									<?php
										}
										else{
									?>
											<td width="68"><a style="display:block" href="<?=$course->getJobProfile()->getCareerServicesLink()?>"><img src="<?=SHIKSHA_HOME?>/public/images/info-icon.jpg" /></a></td>
											<td><a href="<?=$course->getJobProfile()->getCareerServicesLink()?>">Placement<br />Services</a></td>
									<?php 
										}
									?>
								</tr>   
							</table>
						</div>
				<?php
					}
				?>
            </div>    <!-- 3rd page ends here	-->
			<div class="content-child">
				<?php
					if(!empty($currencySymbolMapping[$course->getJobProfile()->getAverageSalaryCurrencyId()]) && $course->getJobProfile()->getAverageSalary() !=''){ //If we have a mapping
				?>
						<div class="content-details clearfix">
							<h2>Placement Information</h2>
							<p>Avg Salary / Package</p>
							<table class="course-fee-table">
								<tr>
									<td width="68">
										<div class="currency-box"><?=$course->getJobProfile()->getAverageSalaryCurrencyId()==1?$inrSymbolImage:$currencySymbolMapping[$course->getJobProfile()->getAverageSalaryCurrencyId()]?></div>
									</td>
									<td width="100" valign="bottom">
										<strong>
											<?=ltrim($course->getJobProfile()->getAverageSalary(),'0')?>
										</strong>
										<br />Annually
									</td>
									<?php
									if($course->getJobProfile()->getAverageSalaryCurrencyId()!=1){
									?>
										<td width="20">
											<strong>=</strong>
										</td>
										<td width="68">
											<div class="currency-box"><?=$inrSymbolImage?></div>
										</td>
										<td width="100" valign="bottom">
											<strong style="font-family:OpenSans-Bold"><?=$avgSalaryINR?></strong>
											<br />Annually
										</td>
										<td width="300">
											<div style="width:300px;"></div>
										</td>
									<?php
									}else{
									?>
										<td width="20">
										</td>
										<td width="68">
										</td>
										<td width="100" valign="bottom">
										</td>
										<td width="300">
											<div style="width:300px;"></div>
										</td>
									<?php
									}
									?>
								</tr>
							</table>    
						</div>
				<?
					}elseif($course->getJobProfile()->getAverageSalaryCurrencyId()!=0 && $course->getJobProfile()->getAverageSalary()!=''){ //If we don't have a mapping but have a currency
				?>
						<div class="content-details clearfix">
							<h2>Placement Information</h2>
							<p>Avg Salary / Package</p>
							<table class="course-fee-table">
								<tr>
									<td width="68">
										<?php $currencySize = 44 - 5*strlen($course->getJobProfile()->getCurrencyEntity()->getCode()); ?>
										<div class="currency-box" style='font-size: <?=$currencySize?>px !important'><b><?=$course->getJobProfile()->getAverageSalaryCurrencyId()==1?$inrSymbolImage:$course->getJobProfile()->getCurrencyEntity()->getCode()?></b></div>
									</td>
									<td width="100" valign="bottom">
										<strong>
											<?=ltrim($course->getJobProfile()->getAverageSalary(),'0')?>
										</strong>
										<br />Annually
									</td>
									<?php
									if($course->getJobProfile()->getAverageSalaryCurrencyId()!=1){
									?>
										<td width="20">
											<strong>=</strong>
										</td>
										<td width="68">
											<div class="currency-box"><?=$inrSymbolImage?></div>
										</td>
										<td width="100" valign="bottom">
											<strong style="font-family:OpenSans-Bold"><?=$avgSalaryINR?></strong>
											<br />Annually
										</td>
										<td width="300">
											<div style="width:300px;"></div>
										</td>
									<?php
									}
									?>
								</tr>
							</table>    
						</div>
				<?php
					}
				?>
                <!--</div>-->
				<?php
					if(!is_null($course->getRecruitingCompanies()) && reset($course->getRecruitingCompanies())->getLogoUrl() != ''){
				?>
						<div class="content-details clearfix">
							<?php
							$companies = $course->getRecruitingCompanies();
							$totalCompanies = count($companies);
							$moreCompanies = 0;
							if($totalCompanies>6){
								$moreCompanies = $totalCompanies-6;
							}
							$sets[] = array();
							$i = 0;
							foreach($companies as $tempCompany){
								if($i%3 == 0){
									$sets[$i] = array();
								}
								$sets[floor($i/3)][] = $tempCompany;
								$i++;
							}
							?>
							<table width="100%">
								<tr>
									<td>
										<h2>Recruiting Companies</h2>
									</td>
									<?php if($moreCompanies > 0){ ?>
										<td align="right"><a href="<?=$course->getURL()?>" class="more-link">More >></a></td>
									<?php } ?>
								</tr>
							</table>
							<table class="companies-table">
								<?php for($i=0;$i<min(2,count($sets));$i++){ ?>
									<tr>
										<?php
										$set = $sets[$i];
										foreach($set as $company){
										?>
											<td><img src="<?=$company->getLogoUrl();?>" /></td>
										<?php
										}
										?>
									</tr>
								<?php } ?>
							</table>
						</div>
				<?php
					}
					if($course->getJobProfile()->getPercentageEmployed()!='' || $course->getJobProfile()->getPopularSectors()!=''){
				?>
				
						<div class="content-details clearfix ">
							<?php if($course->getJobProfile()->getPercentageEmployed()!=''){?><h2>Percentage of students employed: <?=$course->getJobProfile()->getPercentageEmployed()?></h2><?php }?>
							<?php if($course->getJobProfile()->getPopularSectors()!=''){?>
							<p>Popular sectors</p>   
							<div class="wiki-content">
								<table width="100%" style="margin-top:5px">
									<tr>
										<td>
											<?=str_replace("/mediadata/images/",SHIKSHA_HOME."/mediadata/images/",$course->getJobProfile()->getPopularSectors())?>
										</td>
									</tr>
								</table>
							</div>
							<?php }?>
						</div>
				<?php
					}
				?>
			</div>
			<?php
			if(''.$course->getClassProfile()->getAverageWorkExperience().''.$course->getClassProfile()->getAverageGPA().''.$course->getClassProfile()->getAverageGMATScore().''.$course->getClassProfile()->getAverageAge().''.$course->getClassProfile()->getPercenatgeInternationalStudents() != ''){
			?>
				<div class="note-box profile-data clearfix">
					<h2>Students studying this Course (Class Profile)</h2>
					<ul>
						<?php if($course->getClassProfile()->getAverageWorkExperience()!=''){ ?>
							<li>
								<label>Average Work Experience</label>
								<span><?=$course->getClassProfile()->getAverageWorkExperience()?></span>
							</li>
						<?php } ?>
						<?php if($course->getClassProfile()->getAverageGPA()!=''){ ?>
							<li>
								<label>Average Bachelors GPA / Percentage</label>
								<span><?=$course->getClassProfile()->getAverageGPA()?></span>
							</li>
						<?php } ?>
						<?php if($course->getClassProfile()->getAverageGMATScore()!=''){ ?>
							<li>
								<label>Average GMAT Score</label>
								<span><?=$course->getClassProfile()->getAverageGMATScore()?></span>
							</li>
						<?php } ?>
						<?php if($course->getClassProfile()->getAverageAge()!=''){ ?>
							<li>
								<label>Average Student Age</label>
								<span><?=$course->getClassProfile()->getAverageAge()?> years</span>
							</li>
						<?php } ?>
						<?php if($course->getClassProfile()->getPercenatgeInternationalStudents()!=''){ ?>
							<li>
								<label>Percentage of International Student</label>
								<span><?=$course->getClassProfile()->getPercenatgeInternationalStudents()?></span>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php
			}
			?>
			<?php
				if($course->getJobProfile()->getInternships() != ''){
			?>
					<div class="content-child">
						<div class="content-details clearfix ">
							<h2>Internships</h2> 
							<div class="wiki-content">
								<?=str_replace("/mediadata/images/",SHIKSHA_HOME."/mediadata/images/",$course->getJobProfile()->getInternships());?>
							</div>  
						</div>
					</div>
			<?php
				}
			?>
			<!-- 4th page ends here	-->
			<?php
				$photo1 = '';
				$photo2 = '';
				$video = '';
				$videoURL = '';
				foreach($university->getMedia() as $mediaItem){
					if($photo1 == '' and $mediaItem->getType() == 'photo' and $mediaItem->getURL()!=''){
						$photo1 = $mediaItem->getURL();
					}
					else if($photo2 == '' and $mediaItem->getType() == 'photo' and $mediaItem->getURL() != $photo1 and $mediaItem->getURL()!=''){
						$photo2 = $mediaItem->getURL();
					}
					else if($video == '' and $mediaItem->getType()=='video' and $mediaItem->getThumbURL()!=''){
						$video = $mediaItem->getThumbURL();
						$videoURL = $mediaItem->getURL();
					}
				}
				if(''.$photo1.$photo2.$video!=''){
			?>
					<div class="content-child">
						<div class="content-details clearfix ">
							<h2>University Glimpses</h2>
							
							<table class="image-table">
								<?php if($photo1!= ''){ ?>
								<tr>
									<td align="center"><div style="width:400px; height:268px; margin: 0 auto"><a style="display:block;" href="<?=$course->getURL()?>"><img src="<?=$photo1?>"/></a></div></td>
								</tr>
								<?php } ?>
								<?php if($video!=''){?>
								<tr>
									<td align="center">
										<div style="position:relative; width:400px; height:268px; margin: 0 auto">
										<a style="display:block;" href="<?=$course->getURL()?>">
											<span class="play-icon"><img src="<?=SHIKSHA_HOME?>/public/images/play-icon.png" width="77" height="77"/></span>
											<img src="<?=$video?>"/>
										</a>
										</div>
									</td>
								</tr>
								<?php } ?>
								<?php if($photo2!=''){?>
								<tr>
									<td align="center"><div style="position:relative; width:400px; height:268px; margin: 0 auto"><a style="display:block;" href="<?=$course->getURL()?>"><img src="<?=$photo2?>"/></a></div></td>
								</tr>
								<?php } ?>
							</table>
						</div>
					</div>
			<?php
				}
			?>
			<!--  5th page ends here	-->
			<div class="content-child">
				<?php if(!empty($otherCourseData) or !empty($recommendedCourses)){ ?>
					<div class="content-details clearfix" style="padding-bottom:100px; margin-bottom:30px">
						<h2>Other Courses</h2>
						<?php
						if(!empty($otherCourseData)){
						?>
							<table class="other-courses">
								<tr>
									<th>At This University</th>
								</tr>
								<?php foreach($otherCourseData as $otherCourse){?>
									<tr>
										<td><a href="<?=$otherCourse['url']?>"><?=$otherCourse['name']?></a></td>
									</tr>
								<?php } ?>
							</table>
						<?php
						}
						if(!empty($recommendedCourses)){
						?>
							<table class="other-courses dep-table" cellpadding="0">
								<tr>
									<th colspan="2">Recommended For You
									</th>
								</tr>
								<?php foreach($recommendedCourses as $recCourse){ ?>
									<tr>
										<td style="align:left !important"><a href="<?=$recCourse->getURL()?>"><?=$recCourse->getName()?> in <?=$recCourse->getUniversityName()?></a></td>
									</tr>
								<?php } ?>
							</table>
						<?php
						}
						?>
					</div>
				<?php } ?>
				<?php
				$contactString = '';
				$contactString .= $university->getLocation()->getAddress();
				$contactString .= $university->getContactDetails()->getContactEmail();
				$contactString .= $university->getContactDetails()->getContactWebsite();
				$contactString .= $university->getContactDetails()->getContactMainPhone();
				if($contactString!=''){
				?>
                <div class="content-details clearfix" style="margin-bottom:0px; border-bottom:0px none">
					<h2 style="margin-bottom:25px">University Contact Details</h2> 
                    <h2><?=$university->getName()?></h2>
                    <p>
						<?php if($university->getLocation()->getAddress()!=''){ echo $university->getLocation()->getAddress(); } ?><br />
						<?php if($university->getContactDetails()->getContactEmail()!=''){ ?>  Email: <a href="mailto:<?=$university->getContactDetails()->getContactEmail()?>"><?=$university->getContactDetails()->getContactEmail()?></a><br />  <?php } ?>
						<?php if($university->getContactDetails()->getContactWebsite()!=''){ ?>  Website: <a href="<?=$university->getContactDetails()->getContactWebsite()?>"><?=$university->getContactDetails()->getContactWebsite()?></a><br />  <?php } ?>
						<?php if($university->getContactDetails()->getContactMainPhone()!=''){ ?>  Contact No.: <?=$university->getContactDetails()->getContactMainPhone()?>  <?php } ?>
					</p>
                </div>
				<?php
				}
				?>
				<div class="disclaimer-content">
					<p><b>Disclaimer: </b>This is not an official brochure of <?=$university->getName()?>. It was auto-generated based on the information available on studyabroad.shiksha.com. Trademarks belong to respective owners.</p>
				</div>
			</div>    
		</div>
	</div>
</body>
</html>
