<?php
	$this->load->view('/mcommon/header');
?>
<div id="head-sep"></div>
<div class="gray-bar"><a class="back-link" href="<?php echo $referral_url;?>">back to search results</a></div>
<div class="search-list-cont">
<?php foreach ($results as $cat_id=>$courses):
$temp_course = reset($courses);
?>
			<h6>
				Course in <?php echo $temp_course['cat_name']?$temp_course['cat_name']:"Others";?> - <a><?php echo getPlural(count($courses), 'course');?></a>
			</h6>
			<ol>
		<?php foreach($courses as $course):?>
				<li>
					<p>
						<a href="<?php echo $course['url'];?>"><?php echo $course['name'];?></a>, 
				<span> <?php echo $course['courseduration'] ? $course['courseduration'] : ""; ?>
				<?php echo ( $course['courseduration']  && $course['coursetype']  ) ? ", " . $course['coursetype'] : ( $course['coursetype'] ? $course['coursetype'] : "" ); ?>
				<?php echo ( $course['courselevel'] && ($course['coursetype'] || $course['courseduration'])) ? ", ".  $course['courselevel'] : (  $course['courselevel'] ?  $course['courselevel'] : "");?>
				</span>
					</p> <?php if( $course['coursefeesvalue']):?>Fees: <?php echo $course['coursefeesunit']." ".$course['coursefeesvalue'] ;?><?php endif;?>
					<div class="spacer8 clearFix"></div>
					<?php if($course['ispaid']):?>
					<?php if(in_array($course['id'],$applied_courses)):?>
					<div class="apply_confirmation">
					E-Brochure successfully mailed
					</div>
					<?php else:?>	 
					<form method="post" action="/muser/MobileUser/renderRequestEbrouchre">
						<input type="hidden" name="from_where" value="SEARCH"/>
						<input type="hidden" name="selected_course" value="<?php echo $course['id'];?>"/>
				    		<input type="hidden" value="<?php echo $course['addReqInfoVars'];?>" name="courseAttr"/>
				    		<input type="hidden" value="<?php echo url_base64_encode($course['current_url']);?>" name="current_url"/> 
				    		<input type="hidden" value="<?php echo url_base64_encode($course['referral_url']);?>" name="referral_url"/>
				    		<input class="brochure-btn" type="submit" value="Request E-brochure" />
				    	</form>
					<?php endif;?>
					<?php endif;?>
				</li>
		<?php endforeach;?>	
			</ol>
<?php endforeach;?>
<?php 
	$this->load->view('/mcommon/footer');
?>
