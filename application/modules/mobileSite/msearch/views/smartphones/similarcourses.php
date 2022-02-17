<?php
	$this->load->view('/mcommon/header');
?>	
<div id="head-sep"></div>	
<div class="gray-bar"><a class="back-link" href="<?php echo $referral_url;?>">back to search results</a></div>
<div style="padding-bottom:3px" class="inst-box">
	<div class="search-title">
	<?php 
	$temp_course = reset($results);
	if(count($results)>1) {
		echo count($results)." courses similar to ".'<span>"'.$temp_course['similarname'].'"</span>';
	} else {
		echo count($results)." course similar to ".'"'.$temp_course['similarname'].'"';
	}
	?>	
    </div>
    
    
    <div class="clearFix"></div>
    <span class="cloud-arr">&nbsp;</span>
	</div>


<div id="content-wrap">
	<div id="contents">
    	<ul>
	<?php foreach($results as $course):?>
        	<li>
            	<div class="similar-courses">
            		<a href="<?php echo $course['url'];?>"><?php echo $course['name'];?></a>
                    <p>- <?php echo $course['courseduration'] ? $course['courseduration'] : ""; ?>
				<?php echo ( $course['courseduration']  && $course['coursetype']  ) ? ", " . $course['coursetype'] : ( $course['coursetype'] ? $course['coursetype'] : "" ); ?>
				<?php echo ( $course['courselevel'] && ($course['coursetype'] || $course['courseduration'])) ? ", ".  $course['courselevel'] : (  $course['courselevel'] ?  $course['courselevel'] : "");?></p>
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
		        <input type="hidden" value="<?php echo url_base64_encode(urldecode($course['current_url']));?>" name="current_url"/> 
			<input type="hidden" value="<?php echo url_base64_encode($course['referral_url']);?>" name="referral_url"/>
			<input class="brochure-btn" type="submit" value="Request E-brochure" />
		    </form>
		    <?php endif; ?>	
		    <?php endif; ?>	
                </div>
                
                </li> 
	<?php endforeach;?>	
        </ul>
    </div>

<?php 
	$this->load->view('/mcommon/footer',array('total_results'=>count($results)));
?>
