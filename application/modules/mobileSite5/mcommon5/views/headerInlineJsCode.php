<script type="text/javascript">
	var t_jsstart = new Date().getTime();
	COOKIEDOMAIN = '<?=COOKIEDOMAIN?>';
	<?php 
		if(is_array($userStatus) && ($userStatus != 'false')) {
		    $logged_in_user_array = $userStatus;
		} else {
		    $logged_in_user_array = $this->data['m_loggedin_userDetail'];
		}

		if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false') {
			$logged_in_user_array = array();
		} else {
			$logged_in_user_array = $logged_in_user_array[0];
		}

		global $user_logged_in, $logged_in_userid, $shiksha_site_current_url, $shiksha_site_current_refferal, $logged_in_usermobile, $logged_in_user_name, $logged_in_first_name, $logged_in_last_name, $logged_in_user_email, $logged_in_user_city, $logged_in_user_graduation_year, $logged_in_user_xii_year, $logged_in_user_avtar_url;
		$logged_in_userid 			= (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];
		$user_logged_in 			= (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';
		$logged_in_usermobile 		= (!isset($logged_in_user_array['mobile'])) ? '-1' : $logged_in_user_array['mobile'];
		$logged_in_user_name 		= (!isset($logged_in_user_array['displayname'])) ? 'empty' : $logged_in_user_array['displayname'];
		$logged_in_user_email 		= (!isset($logged_in_user_array['cookiestr'])) ? 'empty' : $logged_in_user_array['cookiestr'];
		$values 					= explode("|",$logged_in_user_email);
		$logged_in_user_email 		= $values[0];
		$logged_in_first_name 		= (!isset($logged_in_user_array['firstname'])) ? 'empty' : $logged_in_user_array['firstname'];
		$logged_in_last_name        = (!isset($logged_in_user_array['lastname'])) ? 'empty' : $logged_in_user_array['lastname'];
		$logged_in_user_city        = (!isset($logged_in_user_array['city'])) ? 'empty' : $logged_in_user_array['city']; 
		$shiksha_site_current_url 	= current_url();
		if($_SERVER['HTTP_REFERER']) {
			$shiksha_site_current_refferal =  htmlentities(strip_tags($_SERVER['HTTP_REFERER']));
		} else {
			$shiksha_site_current_refferal = "www.shiksha.com";	
		}
		$encoded_current_url 			= url_base64_encode($shiksha_site_current_url);
		$encoded_current_refferal 		= url_base64_encode($shiksha_site_current_refferal);
		$logged_in_user_avtar_url 	    = (empty($logged_in_user_array['avtarurl'])) ? 'empty' : addingDomainNameToUrl(array('url'=>$logged_in_user_array['avtarurl'],'domainName'=>MEDIA_SERVER));
		global $validDomains;
		global $invalidEmailDomains; 
	?>
	var validDomains = <?php echo json_encode($validDomains); ?>;
	var invalidDomains = <?php echo json_encode($invalidEmailDomains); ?>;
	var MOBILE_SEARCH_V2_INTEGRATION_FLAG = <?php echo MOBILE_SEARCH_V2_INTEGRATION_FLAG; ?>;
	base_url="<?php echo SHIKSHA_HOME;?>";shiksha_site_current_url="<?php echo $shiksha_site_current_url; ?>";shiksha_site_current_refferal="<?php echo $shiksha_site_current_refferal; ?>";logged_in_user_first_name="<?php echo addslashes($logged_in_first_name); ?>";logged_in_user_last_name="<?php echo addslashes($logged_in_last_name) ;?>";logged_in_user_name="<?php echo addslashes($logged_in_user_name);?>";is_user_logged_in="<?php echo $user_logged_in;?>";logged_in_userid="<?php echo $logged_in_userid;?>";logged_in_mobile="<?php echo $logged_in_usermobile;?>";logged_in_email="<?php echo $logged_in_user_email;?>";logged_in_user_city="<?php echo $logged_in_user_city;?>";logged_in_user_graduation_year="<?php echo $logged_in_user_graduation_year;?>";logged_in_user_xii_year="<?php echo $logged_in_user_xii_year;?>";logged_in_user_avtar_url="<?=$logged_in_user_avtar_url;?>";
	<?php if (getTempUserData('flag_google_adservices')){ ?>
		var img = document.createElement("img"), goalId = 984794453, randomNum = new Date().getMilliseconds(), value = 0, label = "m2i6CIvb_QQQ1YrL1QM", url = encodeURI(location.href);
		var trackUrl = "http://www.googleadservices.com/pagead/conversion/"+goalId+"/?random="+randomNum+"&value="+value+"&label="+label+"&guid=ON&script=0&url="+url;
		img.src = trackUrl;
		document.body.appendChild(img);
	<?php }
	deleteTempUserData('flag_google_adservices');
	// set these variable to make common autosuggestor function in main.js
	if((isset($searchPage) && $searchPage!='') || (isset($collegeReviewPage) && $collegeReviewPage!='') || MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1){ ?>
		var mobileSearch = 'true', fromSearchPage = '<?php if(isset($fromSearchPage)){echo $fromSearchPage;}?>', isPopulate = '<?php if(isset($isPopulate)){echo $isPopulate;}?>', searchFrom = '<?php if(isset($searchFrom)){echo $searchFrom;}?>', totalResult= '<?php if(isset($totalResult)){echo $totalResult;}?>', schemaName = '<?php if(isset($schemaName)){echo $schemaName;}?>', inputKeyId = '<?php if(isset($inputKeyId)){ echo $inputKeyId;}?>', container  = '<?php if(isset($container)){ echo $container;}?>', SEARCH_PAGE_URL_PREFIX = '<?php echo SEARCH_PAGE_URL_PREFIX; ?>';
	<?php } ?>
	var boomerPageName = '<?php echo strtoupper($boomr_pageid);?>';
</script>
