	<?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name'])){ ?>
    <div id="breadcrumb">
    	<ul>
	    <li><a href="<?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']; ?>" title="Application Forms">Application Forms</a></li>
		<li><a href="/studentFormsDashBoard/StudentDashBoard/index" title="Application Forms">My Dashboard</a></li>
            <li><?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name'];?></li>
            <li class="last"><?php if($instituteInfo[0]['instituteInfo'][0]['institute_id']==52030){echo "B.Tech";}else{ echo $instituteInfo[0]['instituteInfo'][0]['courseTitle'];}?></li>
        </ul>
    </div>
    <?php }else{ ?>
    <div id="breadcrumb">
    	<ul>
	    <li><a href="<?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']; ?>" title="Application Forms">Application Forms</a></li>
		<li><a href="/studentFormsDashBoard/StudentDashBoard/index" title="Application Forms">My Dashboard</a></li>
            <li class="last">User Profile</li>
        </ul>
    </div>
    <?php } ?>
	
	<?php if(!$courseId) { ?>
	<div class="helpBox2">
		<span>
		<a title="Help" class="helpIcon" onclick="showHelpLayer();" href="javascript:void(0);">Help</a>
		<a title="Faqs" class="faqsIcon" onclick="showFaqLayer();" href="javascript:void(0);">Faqs</a>
		</span>
	</div>
	<?php } ?>
