<section class="clearfix content-section">
<p class="mentor-title">Your Mentor</p>
	<div class="mentor-widget-box clearfix">
	<p>
    <strong style="color:#006fa2;font-size:14px;word-wrap:break-word">
    <?php echo $mentorDetails[0]['ca']['displayName'];?>
    </strong>
    <span style="margin:0 8px;">|</span>
    <span><?php if($mentorDetails[0]['ca']['mainEducationDetails'][0]['badge']=='CurrentStudent'){ echo 'Current Student';}?>
    </span></p>
    <ul>
    	<li>
        	<label>College:</label>
            <a href="<?php echo $instObj->getUrl();?>" class="mentor-info"><?php echo $courseObj->getInstituteName();?></a>
        </li>
        <li>
        	<label>Branch:</label>
            <a href="<?php echo $courseObj->getUrl();?>" class="mentor-info"><?php echo $courseObj->getName();?></a>
        </li>
        <li class="last">
        	<label>City:</label>
            <p class="mentor-info-2"><?php echo $courseObj->getMainLocation()->getCity()->getName();?></p>
        </li>
    </ul>	
</div>
</section>