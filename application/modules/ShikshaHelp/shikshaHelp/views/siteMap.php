<?php  
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
						'css'	=>	array('static'),
						'jsFooter'=>    array('common'),
						'title'	=>	'Site Map',
						'tabName' =>	'Site Map',
						'taburl' =>  site_url(),
						'metaKeywords'	=>'Some Meta Keywords',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
					    'bannerProperties' => array('pageId'=>'', 'pageZone'=>''),
						'product'	=>'Site Map',
                                                'callShiksha'=>1
					);
		$this->load->view('common/homepage', $headerComponents);
		$eduInfo = array('Study Abroad' => array('url'=>SHIKSHA_STUDYABROAD_HOME),
				 'Test Preparation'  => array('url'=>SHIKSHA_TESTPREP_HOME),
				 'Events' => array('url'=>SHIKSHA_EVENTS_HOME),
				 'Ask & Answer' => array('url'=>SHIKSHA_ASK_HOME)
				 	);
		$networkInfo = array('Institute Groups' => array('url'=>SHIKSHA_GROUPS_HOME),
				 'School Groups'  => array('url'=>SHIKSHA_SCHOOL_HOME)
				 	);
                    $mailboxurl = base64_encode(SHIKSHA_HOME.'/mail/Mail/mailbox');
        $personalInfo = array('Messages' => array('url'=>SHIKSHA_HOME.'/mail/Mail/mailbox','onClick'=>'showuserLoginOverLay(this,"SHIKSHA_SITEMAP_LEFTPANEL_MESSAGES","redirect","'.$mailboxurl.'");'),
                     'Alerts'  => array('url'=>SHIKSHA_HOME .'/alerts/Alerts/alertsHome','onClick'=>'window.location = "/alerts/Alerts/alertsHome"'));
		$loggedInUserId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	
?>
<div class="mar_full_10p normaltxt_11p_blk">
	<!-- <div><a href="#">Home</a> &gt; Sitemap</div> -->

<!--Start_Explore Colleges by Career Option-->	
	<div class="lineSpace_10">&nbsp;</div>
	<div class="OrgangeFont fontSize_14p bld">Explore Institutes by Career Option</div>
	<div class="lineSpace_5">&nbsp;</div>
	<div class="grayLine"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="row">
		<?php $i=0; foreach($categoryParentMap as $key => $record):
			$i++;
			if(($i%2) == 0): ?>		
		<div class="float_R" style="width:46%">
			<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo constant('SHIKSHA_'. strtoupper($record['url']) .'_HOME'); ?>" class="fontSize_12p"><?php echo $key; ?></a></div></div>
		</div>
		<?php else: ?>
		<div style="width:46%">
			<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo constant('SHIKSHA_'. strtoupper($record['url']) .'_HOME'); ?>" class="fontSize_12p"><?php echo $key; ?></a></div></div>
		</div>
		<?php endif; endforeach; ?>
		<div class="clear_R"></div>
	</div>
<!--End_Explore Colleges by Career Option-->	

<!--Start_Explore Colleges by Career Countries-->	
	<div class="lineSpace_20">&nbsp;</div>
	<div class="OrgangeFont fontSize_14p bld">Explore Institutes by Countries</div>
	<div class="lineSpace_5">&nbsp;</div>
	<div class="grayLine"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="row">	
		<div class="float_R" style="width:46%">
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['australia']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_AUSTRALIA_HOME; ?>" class="fontSize_12p"><?php echo $countries['australia']['name']; ?></a></div></div>
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['newzealand']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_NEWZEALAND_HOME; ?>" class="fontSize_12p"><?php echo $countries['newzealand']['name']; ?></a></div></div>
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['uk']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_UK_HOME; ?>" class="fontSize_12p"><?php echo $countries['uk']['name']; ?></a></div></div>
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['germany']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_GERMANY_HOME; ?>" class="fontSize_12p"><?php echo $countries['germany']['name']; ?></a></div></div>
		</div>
		<div style="width:46%">
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['india']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_INDIA_HOME; ?>" class="fontSize_12p"><?php echo $countries['india']['name']; ?></a></div></div>
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['canada']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_CANADA_HOME; ?>" class="fontSize_12p"><?php echo $countries['canada']['name']; ?></a></div></div>
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['singapore']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_SINGAPORE_HOME; ?>" class="fontSize_12p"><?php echo $countries['singapore']['name']; ?></a></div></div>
			<div class="mar_bottom_10p"><div><img src="<?php echo $countries['usa']['flagImage']; ?>" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_USA_HOME; ?>" class="fontSize_12p"><?php echo $countries['usa']['name']; ?></a></div></div>
		</div>
		<div class="clear_R"></div>
	</div>	
<!--End_Explore Colleges by Career Countries-->	

<!--Start_Education/Network-->	
	<div class="lineSpace_20">&nbsp;</div>
	<div class="row">
<!--		<div class="float_R" style="width:46%">
				<div class="OrgangeFont fontSize_14p bld">Network</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<?php  foreach($networkInfo as $key => $record) : ?>	
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo $record['url'] ?>" class="fontSize_12p"><?php echo $key; ?></a></div></div>
				<?php endforeach; ?>
		</div>-->
		<div style="width:100%">
			<div class="mar_right_10p">
				<div class="OrgangeFont fontSize_14p bld">Education Information</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<?php  //foreach($eduInfo as $key => $record) : ?>
				<div class="mar_bottom_10p">
					<!--<div>
						<img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" />-->
						<?php 
							echo '<div style="padding-bottom:5px"><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="'.SHIKSHA_USA_HOME.'" class="fontSize_12p">Study Abroad</a></div>';
							echo '<div style="padding-bottom:5px"><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="'.SHIKSHA_TESTPREP_HOME.'" class="fontSize_12p">Test Preparation</a></div>';
							echo '<div style="padding-bottom:5px"><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="'.SHIKSHA_EVENTS_HOME_URL.'" class="fontSize_12p">Events</a></div>';
							echo '<div style="padding-bottom:5px"><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="'.SHIKSHA_ASK_HOME_URL.'" class="fontSize_12p">Ask & Answer</a></div>';
						?>
						<!--<a href="<?php echo $record['url'] ?>" class="fontSize_12p"><?php echo $key; ?></a>
					</div>-->
				</div>
				<?php //endforeach; ?>
			</div>		
		</div>
		<div class="clear_R"></div>
	</div>	
<!--End_Education/Network-->	

<!--Start_Personal/Shiksha-->	
	<div class="lineSpace_20">&nbsp;</div>
	<div class="row">
		<div class="float_R" style="width:46%">
				<div class="OrgangeFont fontSize_14p bld">About Shiksha</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_ABOUTUS_HOME; ?>" class="fontSize_12p">About Us</a></div></div>
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo SHIKSHA_FAQ_HOME; ?>" class="fontSize_12p">FAQ</a></div></div>
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="/shikshaHelp/ShikshaHelp/contactUs" class="fontSize_12p">Contact Us</a></div></div>
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="#" class="fontSize_12p" onclick="return showFeedBack();">Feedback</a></div></div>				
		</div>
		<div style="width:50%">
			<div class="mar_right_10p">
				<div class="OrgangeFont fontSize_14p bld">Personal Information</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<?php  foreach($personalInfo as $key => $record) : 
					if($loggedInUserId == 0):
				 ?>			
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a onClick = '<?php echo $record['onClick']?>' class="fontSize_12p" style="cursor:pointer;"><?php echo $key; ?></a></div></div>
				<?php else: ?>
				<div class="mar_bottom_10p"><div><img src="/public/images/grayBullet.gif" align="absmiddle" class="mar_right_5p" /> <a href="<?php echo $record['url'] ?>" class="fontSize_12p"><?php echo $key; ?></a></div></div>
				<?php endif; endforeach; ?>	
			</div>		
		</div>
		<div class="clear_R"></div>
	</div>	
<!--End_Education/Network-->	
	
	

</div>
<?php
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties); 
?>
