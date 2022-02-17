<?php foreach($result->getOtherCareerInformation() as $key=>$value):?>
<?php $$key = $value;?>
<?php endforeach;?>

<?php
$bannerProperties = array('pageId'=>'CAREER_CENTRAL_DETAIL', 'pageZone'=>'HEADER');
$headerComponents = array(
   'js' 	=>	array('common','ajax-api','careers'),
   'callShiksha'=>1,
   'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
   'notShowSearch' => true,
   'showBottomMargin' => false,
   'showApplicationFormHeader' => false,
   'metaDescription' => (!empty($metaTagsDescription)?$metaTagsDescription:'A career option as '.htmlentities($result->getName()).' is one of the best career opportunities up for grabs right now. Read for complete career guidance and lead others in '.htmlentities($result->getName()).' career.'),
   'metaKeywords'  => $metaTagsKeywords,
   'product'=>'CareerProduct',
   'bannerProperties' => $bannerProperties,
   'title'=>htmlentities($result->getName()).' Career Options - Career Guidance for '.htmlentities($result->getName()),
   'articleImage'=>getMediumImage(largeImageUrl(MEDIA_SERVER.$largeImageIntro)),
   'canonicalURL' => $current_page_url
);

$this->load->view('common/header', $headerComponents);
$this->load->view('common/calendardiv');
foreach($result->getOtherCareerInformation() as $k=>$v){
	if($k=='whereToStudyCheckStatus' || $k=='howDoIGetThereCheckStatus' || $k=='employmentOpportunitiesCheckStatus' || $k=='jobProfileCheckStatus'){
		$$k = $v;
	}
}
$jobProfileBar = '';$employmentOpportunitiesBar = '';$howDoIGetThereBar = '';
if($employmentOpportunitiesCheckStatus=='true' || $howDoIGetThereCheckStatus=='true' || $whereToStudyCheckStatus=='true'){
	$jobProfileBar = ' | ';
}
if($howDoIGetThereCheckStatus=='true' || $whereToStudyCheckStatus=='true'){
	$employmentOpportunitiesBar = ' | ';
}
if($whereToStudyCheckStatus=='true'){
	$howDoIGetThereBar = ' | ';
}
?>
    	<!--Code Starts form here-->
    	<div class="career-wrapper" id="page-top">
        	<div class="career-child-content">

                <?php
                $this->load->view('Careers/careerBreadcrumb',array('careerName'=>htmlentities($result->getName())));
                ?>

                <!-- Code Start to Load the Flash banner on the top -->
                <?php
                //$bannerProperties = array('pageId'=>'CAREER', 'pageZone'=>'TOP');
                //$this->load->view('common/banner',$bannerProperties);
                $pageId = 'CAREER'; $pageZone = 'TOP';
                $widthBanner =  constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_WIDTH');
                $heightBanner =  constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_HEIGHT');
                $zoneBanner = constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_ZONE');
                ?>
		<?php //if($result->getCareerId()=='91'){ ?>
                <!-- <div id='careerBanner' style="height:<?php //echo $heightBanner?>px;width:<?php //echo $widthBanner?>px;margin: 0px 0px 30px;float: left;"></div> -->
		<?php //} ?>
                <script>
                var bmsJsUrls=new Array('<?php echo IEPLADS_DOMAIN;?>/bmsjs/bms_display_final.php?zonestr=<?php echo $zoneBanner?>&showall=1&data=&shikshaCriteria=|||&JavaScriptFormat=1&shikshaCareer=<?php echo $result->getCareerId()?>');
                var widthBanner = '<?php echo $widthBanner?>';
                var heightBanner = '<?php echo $heightBanner?>';
                </script>
                <!-- Code end to Load the Flash banner on the top -->

          	<div class="career-leftCol">
                	<h1 class="course-title">Career as <?php echo htmlentities($result->getName());?></h1>
			<?php
			    $url_parts = parse_url("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
			    $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
			    $urlCareer = urlencode($constructed_url);
			?>
 <div class="social-cont">
                        <div class="socila-icons">
                        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td>

                                        <div class="flLt">
                                            <iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlCareer; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>" colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:81px; height:25px"></iframe>
                                        </div>
					<div class="flLt">
                                            <iframe id="twitterFrame" allowtransparency="true" frameborder="0" scrolling="no"  src="about:blank" style="width:82px; height:20px;"></iframe>
                                        </div>
				</td></tr>
			</table>
		</div></div>
                    <p class="description"><?php echo $result->getShortDescription();?></p>
                    <?php if($whereToStudyCheckStatus=='true' || $howDoIGetThereCheckStatus=='true' || $employmentOpportunitiesCheckStatus=='true' || $jobProfileCheckStatus=='true'){ ?>
                    <div class="abt-course-nav">
                    	<ul>
                        	<li><strong>About <?php echo htmlentities($result->getName());?> :</strong></li>
<?php if($jobProfileCheckStatus=='true'){?><li><a href="javascript:void(0);" onClick="scrollPage('jobProfile');">Job Profile</a><?php echo $jobProfileBar;?></li><?php } ?>
<?php if($employmentOpportunitiesCheckStatus=='true'){?><li><a href="javascript:void(0);" onClick="scrollPage('employmentOpportunities');">Employment Opportunities</a><?php echo $employmentOpportunitiesBar;?></li> <?php } ?>
<?php if($howDoIGetThereCheckStatus=='true'){?><li><a href="javascript:void(0);" onClick="scrollPage('howDoIGetThere');"">How do I get there?</a><?php echo $howDoIGetThereBar;?></li><?php } ?>
<?php if($whereToStudyCheckStatus=='true'){?><li><a href="javascript:void(0);" onClick="scrollPage('whereTOStudy');">Where to study?</a></li><?php } ?>
                        </ul>
                    </div>
		    <?php } ?>
		    <?php if($jobProfileCheckStatus=='true'){?>
                    <div class="content-sections" id="jobProfile">
                    	<h2><span class="job-icn"></span>Job Profile</h2>
			
                        <div class="course-figure"><img src="<?php echo largeImageUrl(MEDIA_SERVER.$largeImageIntro);?>" alt="Career as <?php echo htmlentities($result->getName());?>" /></div>
                        <div class="course-details">
                        	<div class="tiny-content">
					<?php
					$summaryJPDesc = new tidy();
					$summaryJPDesc->parseString($wikkicontent_jobProfile_description,array('show-body-only'=>true),'utf8');
					$summaryJPDesc->cleanRepair();
					?>
					<?php echo $summaryJPDesc;?>
					</div>
                            <?php
			    $skillRequiredDisplay = 'false';
			    if(!empty($skillRequiredCount)){?>
                            <div class="skill-red-box" id="skillRequirement" style="display: none;">
                            	<h5>Skills required </h5>
				<?php $skillRequiredCountArr = explode(',',$skillRequiredCount);$countOfskillRequiredCountArr = count($skillRequiredCountArr);
?>
				<?php $i=0;
				$htmlContentFirstBox = '<ul class="skill-items">';
				$htmlContentSecondBox = '<ul class="skill-items">';
				foreach($skillRequiredCountArr as $k=>$v) {
					if(trim(${'skillRequired_'.$v})==''){ continue;}
					if($i%2==0){
						$skillRequiredDisplay = 'true';
						$htmlContentFirstBox .= '<li>'.${'skillRequired_'.$v}.'</li>';
					} else {
						$skillRequiredDisplay = 'true';
						$htmlContentSecondBox .= '<li>'.${'skillRequired_'.$v}.'</li>';
					}
					$i++;
				} 

				$htmlContentFirstBox .= '</ul>';
				$htmlContentSecondBox .= '</ul>';

				echo $htmlContentFirstBox.$htmlContentSecondBox;


?>
                            </div>
			    <?php if($skillRequiredDisplay == 'true'){ ?>
				<script>$('skillRequirement').style.display='block';</script>
			    <?php } ?>
			    <?php } ?>
                        </div></div>
                        <?php 
			if(!empty($jobProfileATypicalDay)){
			$width =593;$height=334;
			  preg_match('#(v\/|watch\?v=)([\w\-]+)#', $jobProfileATypicalDay, $match);
			  ?>
			<div class="content-sections">
                        <div class="video-cont">
                            <h3>Video Clip on <?php echo htmlentities($result->getName());?></h3>
                            <div class="video-box">
				<?php echo preg_replace('#((https://)?(www.)?youtube\.com/watch\?[=a-z0-9&_;-]+)#i',"<div align=\"center\"><iframe title=\"YouTube video player\" width=\"$width\" height=\"$height\" src=\"https://www.youtube.com/embed/$match[2]?autoplay=$autoplay\" frameborder=\"0\" allowfullscreen></iframe></div>",$jobProfileATypicalDay);?>
				<p class="studentNote" style="margin-left:85px;">(Video sourced from YouTube.com - <?php echo $jobProfileATypicalDay;?>)</p>
                            </div>
                        </div>
			</div>
                        <?php } ?>
			<?php if(!empty($wikkicontent_jobProfile_clockwork)){?>
			<div class="content-sections">
                        <div class="clock-work-cont">
                        	<h3>Clock work</h3>
                            <div class="tiny-content">
			    <?php
					$summaryJPCW = new tidy();
					$summaryJPCW->parseString($wikkicontent_jobProfile_clockwork,array('show-body-only'=>true),'utf8');
					$summaryJPCW->cleanRepair();
					?>
					<?php echo $summaryJPCW;?></div>
                        </div></div>
			<?php } ?>

			<?php if($jobProfileCount!=''){?>
			<?php $jobProfileCountArr = explode(',',$jobProfileCount);?>
			<?php foreach($jobProfileCountArr as $key=>$value){?>
			<?php if(trim(${'wikkicontent_title_jobProfile_'.$value})!='' || trim(${'wikkicontent_detail_jobProfile_'.$value})!=''){ ?>
			<div class="content-sections">
                        	<h3 style="margin-bottom: 10px; padding-bottom: 4px"><?php echo ${'wikkicontent_title_jobProfile_'.$value}; ?></h3>
				<div class="tiny-content">
				<?php
				${$summaryJP.$value} = new tidy();
				${$summaryJP.$value}->parseString(${'wikkicontent_detail_jobProfile_'.$value},array('show-body-only'=>true),'utf8');
				${$summaryJP.$value}->cleanRepair();
				?>
					<?php echo ${$summaryJP.$value};?>
				</div>
                        </div>
			<?php } ?>
			<?php } ?>
			<?php }?>
                    
                    <?php } ?>
		    <?php if($employmentOpportunitiesCheckStatus=='true'){
			$showEmploymentOpportunities = 'false';
		     ?>
                    <div class="content-sections" id="employmentOpportunities" style="margin-top:10px;">
                    	<h2><span class="emp-icn"></span>Employment Opportunities</h2>
			<?php if(!empty($wikkicontent_employment_description)){ ?>
                        <div class="tiny-content">
			<?php
			$summaryEODesc = new tidy();
			$summaryEODesc->parseString($wikkicontent_employment_description,array('show-body-only'=>true),'utf8');
			$summaryEODesc->cleanRepair();
			?>
			<?php echo $summaryEODesc;?>
			</div>
			<?php } ?>
                        <?php $showRecruitingCompanies = 'false';?>
			<div class="clearFix"></div>
			<div id="rcYellowBackground" style="display: none; margin-top: 18px">
                        <div class="rec-comp">
			    <?php
					$minSalInLacs = $result->getMinimumSalaryInLacs();
					$maxSalInLacs = $result->getMaximumSalaryInLacs();
					$minSalInThousand = $result->getMinimumSalaryInThousand();
					$maxSalInThousand = $result->getMaximumSalaryInThousand();
                                  
                             if(!empty($wikkicontent_detail_employment_earning_0) ||  !empty($minSalInLacs) || !empty($maxSalInLacs) || !empty($minSalInThousand) || !empty($maxSalaryInThousand)){
				$showEmploymentOpportunities = 'true';?>
                            <div class="earning-wrapper">
                                <div class="earning-cont">
				      <?php
                                        if(!empty($minSalInLacs) || !empty($maxSalInLacs) || !empty($minSalInThousand) || !empty($maxSalInThousand))
                                        {
                                                $minSalToDisplay = '';
                                                if($minSalInLacs>1){$minLakh = ' Lakhs ';}else{$minLakh = ' Lakh ';}
                                                if($maxSalInLacs>1){$maxLakh = ' Lakhs ';}else{$maxLakh = ' Lakh ';}
                                                if(!empty($minSalInLacs)){$minSalToDisplay=$minSalInLacs+($minSalInThousand/100).$minLakh; }else{$minSalToDisplay=$minSalInThousand.' Thousand ';}
                                                $maxSalToDisplay = '';
                                                if(!empty($maxSalInLacs)){$maxSalToDisplay=$maxSalInLacs+($maxSalInThousand/100).$maxLakh; }else{$maxSalToDisplay=$maxSalInThousand.' Thousand ';}
                                        ?>
                                                <h3>Earnings</h3>
                                                <div class="tiny-content"><span class="rupee-icn"></span><p class='earning-amt'>
						<?php if((!empty($minSalInLacs) || !empty($minSalInThousand)) && (!empty($maxSalInLacs) || !empty($maxSalInThousand))){
						echo $minSalToDisplay;?> to <?php echo $maxSalToDisplay;?> per annum
						<?php } ?>

						<?php if((empty($minSalInLacs) && empty($minSalInThousand)) && (!empty($maxSalInLacs) || !empty($maxSalInThousand))){
						?> Up to <?php echo $maxSalToDisplay;?> per annum
						<?php } ?>

						<?php if((!empty($minSalInLacs) || !empty($minSalInThousand)) && (empty($maxSalInLacs) && empty($maxSalInThousand))){
						?> Starts from <?php echo $minSalToDisplay;?> per annum
						<?php } ?>

						
						</p>
						<p class="studentNote">(Salary data sourced from PayScale.com)</p></div>
                                                <div class="spacer20 clearFix"></div>
                                        <?php 
                                        }
                                        ?>
					
                                        <?php if(!empty($wikkicontent_detail_employment_earning_0)){
					$showEmploymentOpportunities = 'true';?>
                                        <h3>Earnings Details</h3>
                                        <div class="tiny-content"><?php
					$summaryEE = new tidy();
					$summaryEE->parseString($wikkicontent_detail_employment_earning_0,array('show-body-only'=>true),'utf8');
					$summaryEE->cleanRepair();
					?>
					<?php echo $summaryEE;?>
					</div>
                                        <?php } ?>
                                </div>
                                <div class="earning-graph"><img src="/public/images/careers/graph.jpg" alt="earning-graph" /></div>
                            </div>
                            <?php } 
			    if($showEmploymentOpportunities == 'true'){ ?>
				<script>$('rcYellowBackground').style.display='block';</script>
			    <?php }
			    
			    ?>
                        </div>
			</div>
			<div style="margin-top: 30px;">
			<h3 id="recruitingcompanies" style="display: none;border: none;padding-bottom: 0px;margin-bottom: 15px;">Recruiting Companies</h3>
                            <ul class="comp-logos" id="recruitingcompaniesUl" style="display: none;margin-bottom: 20px;">
                            	<li>
                                     <?php if(!empty($firstCompanyImage)){ $showRecruitingCompanies= 'true';?><img src="<?php echo MEDIA_SERVER.$firstCompanyImage; ?>" alt="<?php echo $firstCompanyName; ?>" /><?php } ?>
                                    <?php if(!empty($firstCompanyName)){ $showRecruitingCompanies= 'true';?><strong><?php echo $firstCompanyName; ?></strong><?php } ?>
                                </li>
                                
                                <li>
                                    <?php if(!empty($secondCompanyImage)){ $showRecruitingCompanies= 'true';?><img src="<?php echo MEDIA_SERVER.$secondCompanyImage;?>" alt="<?php echo $secondCompanyName;?>" /><?php } ?>
                                    <?php if(!empty($secondCompanyName)){ $showRecruitingCompanies= 'true';?><strong><?php echo $secondCompanyName;?></strong><?php } ?>
                                </li>
                                
                                <li>
                                    <?php if(!empty($thirdCompanyImage)){ $showRecruitingCompanies= 'true';?><img src="<?php echo MEDIA_SERVER.$thirdCompanyImage;?>" alt="<?php echo $thirdCompanyName;?>" /><?php } ?>
                                    <?php if(!empty($thirdCompanyName)){ $showRecruitingCompanies= 'true';?><strong><?php echo $thirdCompanyName;?></strong><?php } ?>
                                </li>
                                
                                <li>
                                    <?php if(!empty($forthCompanyImage)){ $showRecruitingCompanies= 'true';?><img src="<?php echo MEDIA_SERVER.$forthCompanyImage;?>" alt="<?php echo $forthCompanyName;?>" /><?php } ?>
                                    <?php if(!empty($forthCompanyName)){ $showRecruitingCompanies= 'true';?><strong><?php echo $forthCompanyName;?></strong><?php } ?>
                                </li>
                            </ul>
			</div>
			
			<?php if(!empty($wikkicontent_employmentOpportunities_recruitingcompany)){ ?>
			<div class="tiny-content"><?php
			$summaryEE = new tidy();
			$summaryEE->parseString($wikkicontent_employmentOpportunities_recruitingcompany,array('show-body-only'=>true),'utf8');
			$summaryEE->cleanRepair();
			?>
			<?php echo $summaryEE;?>
			</div>	
			<?php }?>
			<?php if($showRecruitingCompanies== 'true' || !empty($wikkicontent_employmentOpportunities_recruitingcompany)){?>
			    <script>$('recruitingcompanies').style.display='block';$('recruitingcompaniesUl').style.display='block';</script>
			<?php } ?>
			<?php if($employmentOpportunitiesCount!=''):?>
			<?php $employmentOpportunitiesCountArr = explode(',',$employmentOpportunitiesCount);?>
			<?php foreach($employmentOpportunitiesCountArr as $key=>$value):?>
			<?php if(trim(${'wikkicontent_title_employmentOpportunities_'.$value})!='' || trim(${'wikkicontent_detail_employmentOpportunities_'.$value})!=''){ ?>
			<div class="content-sections" style="margin: 15px 0 0 0">
                        	<h3 style="margin-bottom: 10px; padding-bottom: 4px"><?php echo ${'wikkicontent_title_employmentOpportunities_'.$value};?></h3>
				<div class="tiny-content">
					<?php
					${$summaryEO.$value} = new tidy();
					${$summaryEO.$value}->parseString(${'wikkicontent_detail_employmentOpportunities_'.$value},array('show-body-only'=>true),'utf8');
					${$summaryEO.$value}->cleanRepair();
					?>
					<?php echo ${$summaryEO.$value};?>
				</div>
                        </div>
			<?php } ?>
			<?php endforeach;?>
			<?php endif;?>
                        </div>
                    <?php } ?>
		    <?php if($howDoIGetThereCheckStatus=='true'){?>
                    <div class="content-sections" id="howDoIGetThere" style="margin-top:10px;">
                    	<h2><span class="step-icn"></span>How do I get there?</h2>
			<?php $i=1;?>
                        <?php foreach($result->getCareerPaths() as $res):?>
						<div class="path-cont">
							<label>Path <?php echo $i;?>:</label>
						</div>
                        <div class="path-cont">
                        	<div>
                            <div class="path-steps">
                            	<ul>
                                    
				    <?php
				    $totalPathSteps = count($res->getSteps());
				    $k=1;
				    foreach($res->getSteps() as $stepResults):?>
				    <li <?php if($k==$totalPathSteps){ ?> class="last"<?php } ?>>
                                    	<h5><?php echo truncate($stepResults->getStepTitle(),'15','...','true');?></h5>
                                        <p><?php echo truncate($stepResults->getStepDescription(),'30','...','true');?></p>
					<?php //if($k==$totalPathSteps){ ?>
						<?php //if(!empty( $pathImage)){ ?>
							<!-- <div class="course-pic"><img src="< ?php echo $pathImage;?>"></div> -->
						<?php //} ?>
					<?php //} ?>
                                    </li>
				    <?php $k++;endforeach;?>
                                </ul>
                            </div>
                            </div>
                        </div>
			<?php $i++;?>
                        <?php endforeach; ?>
                        
                        <div class="tiny-content">
                        	<?php
					$summaryHDIGT = new tidy();
					$summaryHDIGT->parseString($wikkicontent_hdigt_detail,array('show-body-only'=>true),'utf8');
					$summaryHDIGT->cleanRepair();
					?>
					<?php echo $summaryHDIGT;?>
                        </div>
			</div>
			<?php if($howDoIGetThereCount!=''):?>
			<?php $howDoIGetThereCountArr = explode(',',$howDoIGetThereCount);?>
			<?php foreach($howDoIGetThereCountArr as $key=>$value):?>
			<?php if(trim(${'wikkicontent_title_howDoIGetThere_'.$value})!='' || trim(${'wikkicontent_detail_howDoIGetThere_'.$value})!=''){ ?>
			<div class="content-sections">
                        	<h3 style="margin-bottom: 10px; padding-bottom: 4px"><?php echo ${'wikkicontent_title_howDoIGetThere_'.$value};?></h3>
				<div class="tiny-content">
					<?php
					${$summaryHDIGT.$value} = new tidy();
					${$summaryHDIGT.$value}->parseString(${'wikkicontent_detail_howDoIGetThere_'.$value},array('show-body-only'=>true),'utf8');
					${$summaryHDIGT.$value}->cleanRepair();
					?>
					<?php echo ${$summaryHDIGT.$value};?>
				</div>
                        </div>
			<?php } ?>
			<?php endforeach;?>
			<?php endif;?>
                    
                    <?php } ?>
		    <?php $prestigiousInsIndiaDisplay = 'No';?>
		    <?php if($whereToStudyCheckStatus=='true'){ ?>
                    <div class="content-sections" id="whereTOStudy" style="display: none;margin-top:10px;" >
                    	<h2><span class="study-icn"></span>Where to study?</h2>
                        <h3 class="section-subTitle" id="presInsIndiaHeading" style="display: none;">Prestigious Institutes in India</h3>
                        <?php if(!empty($logoImageIndia1) || !empty($logoImageIndia2) || !empty($logoImageIndia3) || !empty($logoImageIndia4)){ ?>
                        <ul class="comp-logos">
                            <?php if(!empty($logoImageIndia1)) { $prestigiousInsIndiaDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageIndia1;?>"><strong><?php echo $instituteNameIndia1;?></strong></li><?php } ?>
                            <?php if(!empty($logoImageIndia2)) { $prestigiousInsIndiaDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageIndia2;?>"><strong><?php echo $instituteNameIndia2;?></strong></li><?php } ?>
                            <?php if(!empty($logoImageIndia3)) { $prestigiousInsIndiaDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageIndia3;?>"><strong><?php echo $instituteNameIndia3;?></strong></li><?php } ?>
                            <?php if(!empty($logoImageIndia4)) { $prestigiousInsIndiaDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageIndia4;?>"><strong><?php echo $instituteNameIndia4;?></strong></li><?php } ?>
                        </ul>
			<?php } ?>
			<?php $arr = array('','First','Second','Third','Forth','Fifth');?>
                        <?php $indiawhereToStudyCountArr = explode(',',$indiawhereToStudyCount);?>
			<?php foreach($indiawhereToStudyCountArr as $key=>$value):?>
			<?php ${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionArr'} = explode(',',${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'Section'});?>
                        <div class="course-description" id="indiawhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionDiv" style="display:none;">
			<?php ${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'} = 'No';?>
			<?php ${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'} = 'No';?>
			
                        <?php if(${'indiaHeading_'.$value}!=''){?><h4><?php ${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'} ='Yes';$prestigiousInsIndiaDisplay = 'Yes';echo ${'indiaHeading_'.$value};?></h4><?php } ?>
                            <ul class="bullet-items" id="indiawhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionUl" style="display:none;">
				<?php foreach(${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionArr'} as $k=>$v):?>
				<?php if($instituteDetailIndia['indiaCourseId_'.$arr[$value].'_'.$v]['name']!=''):?>
				<?php $prestigiousInsIndiaDisplay = 'Yes';${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'}='Yes';${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'}='Yes';?>
				<?php if($instituteDetailIndia['indiaCourseId_'.$arr[$value].'_'.$v]['name']!=''){ ?>
				<li><p><a href="<?php echo $instituteDetailIndia['indiaCourseId_'.$arr[$value].'_'.$v]['url'];?>"  rel="nofollow"><?php echo $instituteDetailIndia['indiaCourseId_'.$arr[$value].'_'.$v]['name'];?></a></p></li>
				<?php } ?>
				<?php endif;?>
				<?php if($instituteDetailIndia['indiaCourseText_'.$arr[$value].'_'.$v]['name']!=''):?>
				<?php $prestigiousInsIndiaDisplay = 'Yes';${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'} ='Yes';${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'}='Yes';?>
				<li><p><?php echo $instituteDetailIndia['indiaCourseText_'.$arr[$value].'_'.$v]['name'];?></p></li>
				<?php endif;?>
				<?php endforeach;?>
                              </ul>
                        </div>
			<?php if(${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'}=='Yes'){?>
			<script>$('indiawhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionDiv').style.display='block';</script>
			<?php } ?>
			<?php if(${'indiawhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'}=='Yes'){?>
			<script>$('indiawhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionUl').style.display='block';</script>
			<?php } ?>
			<?php endforeach;?>
                          
                    </div>
                    <h3 class="section-subTitle">Featured Institutes in India</h3>
                    <div>
                    	<?php
							$bannerProperties1 = array('pageId'=>'CAREER_CENTRAL_DETAIL', 'pageZone'=>'BOTTOM', 'shikshaCriteria' => $criteriaArray);
							$this->load->view('common/banner',$bannerProperties1); 
        				?>
                    </div>
                    <?php $prestigiousInsAbroadDisplay = 'No';?>
                    <div class="content-sections" style="display:none;" id="presInsAbroadHeading">
                    	<h3 class="section-subTitle" >Prestigious Institutes in Abroad</h3>
                       <?php if(!empty($logoImageAbroad1) || !empty($logoImageAbroad2) || !empty($logoImageAbroad3) || !empty($logoImageAbroad4)){ ?>
                        <ul class="comp-logos">
                            <?php if(!empty($logoImageAbroad1)){ $prestigiousInsAbroadDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageAbroad1;?>"><strong><?php echo $instituteNameAbroad1;?></strong></li><?php } ?>
                            <?php if(!empty($logoImageAbroad2)){ $prestigiousInsAbroadDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageAbroad2;?>"><strong><?php echo $instituteNameAbroad2;?></strong></li><?php } ?>
                            <?php if(!empty($logoImageAbroad3)){ $prestigiousInsAbroadDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageAbroad3;?>"><strong><?php echo $instituteNameAbroad3;?></strong></li><?php } ?>
                            <?php if(!empty($logoImageAbroad4)){ $prestigiousInsAbroadDisplay = 'Yes';?><li><img alt="" src="<?php echo MEDIA_SERVER.$logoImageAbroad4;?>"><strong><?php echo $instituteNameAbroad4;?></strong></li><?php } ?>
                        </ul>
			<?php } ?>
                        <?php $arr = array('1'=>'First','2'=>'Second','3'=>'Third','4'=>'Forth','5'=>'Fifth')?>
                        <?php $abroadwhereToStudyCountArr = explode(',',$abroadwhereToStudyCount);?>
			<?php foreach($abroadwhereToStudyCountArr as $key=>$value):?>
			<?php ${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionArr'} = explode(',',${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'Section'});?>
                        <div class="course-description" id="abroadwhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionDiv" style="display:none;">
			
                        	<?php if(${'abroadHeading_'.$value}!=''){ ?><h4><?php $prestigiousInsAbroadDisplay = 'Yes';${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'}='Yes';echo ${'abroadHeading_'.$value};?></h4><?php } ?>
			<?php ${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'} = 'No';?>
			<?php ${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'} = 'No';?>
                            <ul class="bullet-items" id="abroadwhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionUl" style="display:none;">
				<?php foreach(${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionArr'} as $k=>$v):?>
				<?php if($instituteDetailAbroad['abroadCourseId_'.$arr[$value].'_'.$v]['name']!=''):?>
				<?php $prestigiousInsAbroadDisplay = 'Yes';${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'} = 'Yes';${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'} = 'Yes';?>
				<?php if($instituteDetailAbroad['abroadCourseId_'.$arr[$value].'_'.$v]['name']!=''){ ?>
                                <li><p><a href="<?php echo $instituteDetailAbroad['abroadCourseId_'.$arr[$value].'_'.$v]['url'];?>"rel="nofollow"><?php echo $instituteDetailAbroad['abroadCourseId_'.$arr[$value].'_'.$v]['name'];?></a></p></li>
				<?php } ?>
				<?php endif;?>
				<?php if($instituteDetailAbroad['abroadCourseText_'.$arr[$value].'_'.$v]['name']!=''):?>
				<?php $prestigiousInsAbroadDisplay = 'Yes';${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'} = 'Yes';${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'} = 'Yes';?>
				<li><p><?php echo $instituteDetailAbroad['abroadCourseText_'.$arr[$value].'_'.$v]['name'];?></p></li>
				<?php endif;?>
				<?php if(${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionDiv'}=='Yes'){?>
					<script>$('abroadwhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionDiv').style.display='block';</script>
				<?php } ?>
				<?php if(${'abroadwhereToStudyCourseIdCountFor'.$arr[$value].'SectionUl'}=='Yes'){?>
					<script>$('abroadwhereToStudyCourseIdCountFor<?php  echo $arr[$value];?>SectionUl').style.display='block';</script>
				<?php } ?>
				<?php endforeach;?>
                            </ul>
                        </div>
			<?php endforeach;?>
                    </div>
		    
		    <?php if($whereToStudyCount!=''):?>
			<?php $prestigiousInsAbroadCustomSectionDisplay = 'No';$prestigiousInsIndiaCustomSectionDisplay = 'No';$whereToStudyCountArr = explode(',',$whereToStudyCount);?>
			<?php foreach($whereToStudyCountArr as $key=>$value):?>
			<?php if(trim(${'wikkicontent_title_whereToStudy_'.$value})!='' || trim(${'wikkicontent_detail_whereToStudy_'.$value})!=''){
			$prestigiousInsAbroadCustomSectionDisplay = 'Yes';$prestigiousInsIndiaCustomSectionDisplay = 'Yes';	
			?>
			<div class="content-sections">
                        	<h3 style="margin-bottom: 10px; padding-bottom: 4px"><?php echo ${'wikkicontent_title_whereToStudy_'.$value};?></h3>
				<div class="tiny-content">
					<?php
					${$summaryWTS.$value} = new tidy();
					${$summaryWTS.$value}->parseString(${'wikkicontent_detail_whereToStudy_'.$value},array('show-body-only'=>true),'utf8');
					${$summaryWTS.$value}->cleanRepair();
					?>
					<?php echo ${$summaryWTS.$value};?>
				</div>
                        </div>
		        <?php } ?>
			<?php endforeach;?>
			<?php endif;?>
		    <?php } ?>
		    <?php 
		
			if(!empty($recommendedOptions)):
				if(count($recommendedOptions)>4){
					$randomCount = 4;
					$rand_keys = array_rand($recommendedOptions, $randomCount);
				}else if(count($recommendedOptions)==1){
					$rand_keys[] = array_rand($recommendedOptions, 1);
				}else{
					$randomCount = count($recommendedOptions);
					$rand_keys = array_rand($recommendedOptions, $randomCount);
				}				
			?>
			
		    <div class="content-sections">
                    	<div class="other-coueses">
                        	<h3>Other careers of your interests</h3>
                            <ul>
				<?php
				for($i=0;$i<count($rand_keys);$i++):?>
				<?php
				$arr = $recommendedOptions[$rand_keys[$i]]->getOtherCareerInformation();
				$imageUrl = $arr['thumbnailImageIntro'];?>
                            	<li>
                                <div class="figure"><img src="<?php echo MEDIA_SERVER.$imageUrl;?>" /></div>
                                    <div class="details"><a href="<?php echo SHIKSHA_HOME;?><?php echo $recommendedOptions[$rand_keys[$i]]->getCareerUrl();?>"><?php echo htmlentities($recommendedOptions[$rand_keys[$i]]->getName());?></a></div>
                                </li>
				<?php endfor;?>
                            </ul>
                            <div class="clearFix"></div>
                        </div>
                        
                    </div>
		    <?php endif;?>
		    <div class="content-sections" style="margin-bottom:55px;">
				<h3 style="margin-bottom: 10px; padding-bottom: 4px"></h3>
				<div class="tiny-content">
				<table border="0">
					<tbody>
						<tr>
						<td width="10%"><img src="https://www.shiksha.com/public/images/careers/kumkum-tandon.jpg" alt="" /></td>
						<td>Content on this page is by Career Expert<br /><strong>Mrs. Kum Kum Tandon</strong><br />MA (Psychology), M.Ed, Diploma in Educational Psychology, Vocational Guidance & Counseling (NCERT, Delhi) | <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/kumkum">View Complete Profile</a></td>
						</tr>
					</tbody>
				</table>
			</div>
			</div>
			<div class="clearFix"></div>
                </div>
                
	
                <div class="career-rightCol" id="career-rightCol">

                	<h3 class="featured-title">Featured Institute</h3>
              
                    
		    <?php if(!empty($featuredColleges[$result->getCareerId()])) { $this->load->view('Careers/featuredColleges',array("featuredColleges" => $featuredColleges[$result->getCareerId()]));} ?>

                    <?php if(!empty($categoryPageURLData) || !empty($rankingPageDetails) || !empty($wikkicontent_entranceexams) || $checkboxLinks=='true'){ ?>
                    <div id="whatnextwidget">

			<div class="what-next-section" style="margin-top:15px;">
			    <label>What Next?<i class="next-dwn-arrow"></i></label>
			</div>

			<?php if(!empty($categoryPageURLData) || !empty($rankingPageDetails) || !empty($wikkicontent_entranceexams)){ ?>
			<div class="career-nxt-widget">
			<i class="career-clg-icon"></i>
			    <?php if(!empty($categoryPageURLData)){ ?>
				    <p class="widget-title">
				    Browse Colleges for this career in your city
			    </p>
			    <ul>
				    <?php foreach($categoryPageURLData as $key=>$value){
				    ?>
					    <li><a href="<?php echo $value['URL'];?>"><?php echo $value['NAME'];?> colleges</a></li>
				    <?php } ?>
    
			    </ul>
			    <?php } ?>
			    <?php if(!empty($rankingPageDetails)){ ?>
			    <div class="career-sep"></div>
			     <p class="widget-title">
				    Top Ranked Colleges for this career across India
			    </p>
			     <ul>
				     <?php foreach($rankingPageDetails as $key=>$rankingDetails){ ?>
                                        <li><a href="<?php echo $rankingDetails['URL'];?>"><?php echo $rankingDetails['NAME'];?> colleges</a></li>
                                    <?php } ?>

			    </ul>
			    <?php } ?>
			    <?php if(!empty($wikkicontent_entranceexams) && (!empty($categoryPageURLData) || !empty($rankingPageDetails))){ ?>
			    <div class="career-sep"></div>
			    <?php } ?>
			    <?php if(!empty($wikkicontent_entranceexams)){ ?>
			    <p class="widget-title">
				    Entrance Exam(s) required for this career
			    </p>
			    <div class="req-exam-list">
				    <?php
					    $summaryJPDesc = new tidy();
					    $summaryJPDesc->parseString($wikkicontent_entranceexams,array('show-body-only'=>true),'utf8');
					    $summaryJPDesc->cleanRepair();
					    ?>
					    <?php echo $summaryJPDesc;?>
			    </div>
			    <?php } ?>
			</div>
			<?php } ?>
			<?php if($checkboxLinks=='true'){ ?>
			<div class="career-nxt-widget">
				    <p class="widget-title">
				    <span style="text-decoration: none;font-weight: normal;">Already given Entrance Exam?<br/></span>
				    Check out if you can make it to your dream college
				    </p>
				    <ul>
						<li><a href="<?php echo SHIKSHA_HOME.'/b-tech/resources/'.$collegePredictorConfig['CPEXAMS']['JEE-MAINS']['collegeUrl'];?>"><?php echo $collegePredictorConfig['CPEXAMS']['JEE-MAINS']['name'];?></a></li>
					    <li><a href="<?php echo SHIKSHA_HOME.'/b-tech/resources/'.$collegePredictorConfig['CPEXAMS']['COMEDK']['collegeUrl'];?>"><?php echo $collegePredictorConfig['CPEXAMS']['COMEDK']['name'];?></a></li>
					    <li><a href="<?php echo SHIKSHA_HOME.'/b-tech/resources/'.$collegePredictorConfig['CPEXAMS']['KCET']['collegeUrl'];?>"><?php echo $collegePredictorConfig['CPEXAMS']['KCET']['name'];?></a></li>					 
				    </ul>
			    <i class="career-exam-icon"></i>
			</div>
			<?php } ?>
		</div>
		    <?php } ?>

<!--Start code added for board right widget by ESHA-->
                <?php $this->load->view('Careers/boardPageWidget')?>
<!--End code added for board right widget by ESHA-->
        </div>

            <div class="clearFix"></div>
            </div>
	    <div class="clearFix"></div>
	    <div id="bottom-widget-display" style="width: 250px; float: right; visibility: hidden"></div>
       </div>
       <!--Code Ends here-->
        
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 

<?php if($validateuser=='false'){ ?>
<script>var floatingRegistrationSource = 'CAREER_FLOATINGWIDGETREGISTRATION';var studyAbroad = 0;</script>
<div id="floatingRegister">
        <script>
		var totalHeight = Math.max($j(document).height(), $j(window).height());
		var floatinWidgetScrollHeight = parseInt((.70) * totalHeight);
		if($j('#footer').offset().top < floatinWidgetScrollHeight) {
		    floatinWidgetScrollHeight = parseInt((0.30) * totalHeight);
		} 
		var careerName = '<?php echo htmlentities($result->getName());?>';
		var trackingPageKeyId = '<?php echo $careerTrackingPageKeyId;?>';
                var jsForWidget = new Array();
                jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("processForm"); ?>');
                if(typeof addWidgetToAjaxList == 'function'){
                	addWidgetToAjaxList('/FloatingRegistration/FloatingRegistration/index/false/'+floatinWidgetScrollHeight+'/bottom-widget-display/0/true/<?php echo $result->getCareerId();?>/\'\'/\'\'/'+trackingPageKeyId,'floatingRegister',jsForWidget);
            	}
		//loadBanner('<?php echo CAREER_DETAIL_PAGE_FLASH_BANNER;?>');
	</script>
	
</div>
<?php } ?>
<?php if($prestigiousInsAbroadDisplay=='Yes'){?>
<script>
	if(typeof $('presInsAbroadHeading') != 'undefined' && $('presInsAbroadHeading') != null){
		$('presInsAbroadHeading').style.display='block';
	}
</script>
<?php }
if($prestigiousInsIndiaDisplay=='Yes'){?>
<script>
	if(typeof $('presInsIndiaHeading') != 'undefined' && $('presInsIndiaHeading') != null){
		$('presInsIndiaHeading').style.display='block';
	}
</script>
<?php }

if($prestigiousInsIndiaDisplay=='Yes' || $prestigiousInsAbroadDisplay=='Yes' || $prestigiousInsAbroadCustomSectionDisplay == 'Yes' || $prestigiousInsIndiaCustomSectionDisplay == 'Yes'){?>
<script>
	if(typeof $('whereTOStudy') != 'undefined' && $('whereTOStudy') != null){
		$('whereTOStudy').style.display='block';
	}
</script>
<?php } ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>" type="text/javascript"></script>
<script>
$j(function() {
		<?php if($result->getCareerId()=='91'){ ?>
		includeBMSJS();
		<?php } ?>
                $j(window).scroll(function() {
					   if(($j(this).scrollTop() >= $j('#page-top').offset().top+($j(document).height()/4))
                           && !(/MSIE ((5\\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32")) {
                                if($j(window).width() < 1000){
                                        $j('#toTop').css('left',($j('#page-top').offset().left+318) + "px");
                                }else{
                                        $j('#toTop').css('left',($j('#page-top').offset().left+425) + "px");
                                }
                                $j('#toTop').fadeIn();
                        } else {
                                $j('#toTop').fadeOut();
                        }
                });
         
                $j('#toTop').click(function() {
                        $j('body,html').animate({scrollTop:0},500);
                });     
        });

</script>
<div id="toTop">&#9650; Back to Top</div>

<script>
/*
    $j(function() {
	if($j('#whatnextwidget').height()>768){
		return;
	}
	if(typeof scrollBackToTop == 'function'){
        scrollBackToTop('whatnextwidget');	
	}
	});
	$j(window).scroll(function() {
	if(typeof isScrolledIntoViewSticky == 'function' && isScrolledIntoViewSticky('#footer')){
	    
	    $j('#whatnextwidget').hide();
	}
	else{
	    $j('#whatnextwidget').show();
	}
     });
*/
</script>
