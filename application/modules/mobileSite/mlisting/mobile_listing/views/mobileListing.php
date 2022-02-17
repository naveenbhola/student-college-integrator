<?php $this->load->view('/mcommon/header'); ?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<div id="head-sep"></div>
<?php global $shiksha_site_current_url;global $shiksha_site_current_referral ;?>  
<?php 
$courses = $institute->getCourses();

foreach($establishedYearAndSeats[0][0] as $nestedArr){

	if($nestedArr['course_id'] == $course_id)
	{
		$seats = $nestedArr['seatsTotal'];
		break;	
	}
}
?>
<div id="listing-wrap">
<h5 class="category-name"><a href="<?php echo $breadCrumb[0][url];?>"><?php echo
$breadCrumb[0][name].' in ' . $currentLocation->getCountry()->getName() . "(" . $currentLocation->getCity()->getName()  . ")";?></a></h5>
<div class="inst-box">
<?php
	if (getTempUserData('confirmation_message')){?>
		<div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
		 <?php echo getTempUserData('confirmation_message'); ?>
		</div> 
	<?php } ?>

<?php	
if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){ ?>
	<div class="figure">
	<img class="lazy" src="/public/images/avatar.gif" data-original="<?php echo base64_encode_image($institute->getMainHeaderImage()->getThumbUrl());?>" width="118"/> </div>
		<?php }else{ ?>
        	<div class="figure">
        				<img class="lazy" src='/public/images/avatar.gif'  data-original="<?php echo base64_encode_image(SHIKSHA_HOME.'/public/images/avatar.gif');?>" width="118"/> </div>
				<?php } ?>
				<div class="inst-details">
				<h4>
				<p><?php echo html_escape($institute->getName());?></p>
				<span><?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName()?></span>
				
				</h4>
				<p><span> <?php if($establishedYearAndSeats['0']['0']['0']['establishedYear'])echo "Established in ".$establishedYearAndSeats['0']['0']['0']['establishedYear'];?></span> 
				<?php 	$addReqInfoVars = array();
				foreach($courses as $c){
					if($c->isPaid()=="TRUE"){
						foreach($c->getLocations() as $course_location){
							$locality_name = $course_location->getLocality()->getName();
							if($locality_name !='') $locality_name = ' | '.$course_location->getLocality()->getName();
							$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
							
						}
					}
				}
				$addReqInfoVars=serialize($addReqInfoVars);
				$addReqInfoVars=base64_encode($addReqInfoVars);
				?>
				<?php if($course->isPaid()=="TRUE"){ ?>
				<form action="/muser/MobileUser/renderRequestEbrouchre/" method="post">
				<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
				<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
				<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_referral); ?>" />
				<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
				<input class="brochure-btn orange-button" type="submit" value="Apply Now" />
				</form>
				<?php }?>
				</p>
				</div>
				<div class="clearFix"></div>
				<span class="cloud-arr">&nbsp;</span>
				</div>
				<?php $this->load->view('see_all_branches_up');?>
				<?php $this->load->view('mobile_contact_details');?>
				<div class="round-box">
				<div class="course-details">
				<h2>Course Details</h2>
				<strong><a href="<?=$course->getURL();?>"><?php  echo $course->getName();?></a></strong>
				<p><?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; ?> <?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?>
				<?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?>
				</p>
				<p>
				</p>
				<p><?php $affiliations = $course->getAffiliations();
				foreach($affiliations as $affiliation) {
					$Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
				}echo $Affiliations[0];	unset($Affiliations);?>
<ul>
</p>
<?php 
	if($course->getFees($currentLocation->getLocationId())->getValue($currentLocation->getLocationId())){?>
		<li><strong>Fees:</strong> <?=$course->getFees($currentLocation->getLocationId());}?>
		</li>
<?php if($seats!='' && $seats!="0"){?><li><strong>Seats:</strong> Total-<?=$seats?></li>
	<?php }?>	<?php $exams = $course->getEligibilityExams();if(count($exams) > 0){ ?>
		<li><strong>Eligibility:</strong>
			<?php
			$examAcronyms = array();
		foreach($exams as $exam) {
			$examAcronyms[] = $exam->getAcronym();
		}
		echo implode(' | ',$examAcronyms); unset($examAcronyms);}?>

		</li>
		</ul>
		<!--      <a class="view-link" href="#">View Contact details</a> -->
		<div class="spacer8 clearFix"></div>
		<!-- <input class="brochure-btn" type="button" value="Request E-brochure" />  -->
		 <?php if($course->isPaid()=="TRUE"){?>		
		<form action="/muser/MobileUser/renderRequestEbrouchre/" method="post">
		<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
		<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
		<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_referral); ?>" />
		<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
		<input class="brochure-btn orange-button" type="submit" value="Request E-brochure" />
		</form>
		<?php }?>
		</div>

		<div class="course-details">
		<?php	$current_course_id = $course_id;$count=count($courses);

		if($count>1){ ?>
			<h2>Other Courses offered</h2>
				<?php foreach($courses as $course1){
					if($current_course_id==$course1->getId()) continue;?>
						<ul>	
							<li><a href="<?=$course1->getURL();?>"><?=$course1->getName();?></a></li>
							<?php }}?>
							</ul>
							</div>
							</div>
							<?php $this->load->view('see_all_branches');?>
							<?php $this->load->view('mobile_alumniSpeak_widget');?>	
							<?php $this->load->view('mobile_why_join');?>
						<?php if($course->isPaid()=="TRUE"){?>
							<div class="round-box">
							<div id="inst-desc">
							<h2>I want this institute to counsel me</h2>
							
							<form action="/muser/MobileUser/renderRequestEbrouchre/" method="post">
							<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
							<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
							<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_referral); ?>" />
							<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
							<input class="brochure-btn orange-button" type="submit" value="Request E-brochure" />
							</form>
							</div>
							</div>
						<?php }?>
</div>
    <?php 
   deleteTempUserData('confirmation_message');
    ?>
 <script>
    $("img.lazy").show().lazyload({ 
        effect : "fadeIn",
        failure_limit : 5 
    });
</script>    
<?php $this->load->view('/mcommon/footer');?>
<?php if(!empty($tracking_url)):?>
<div style="height:1px;overflow:hidden" ><img src="<?php echo $tracking_url; ?>" /></div>
<?php endif;?>

