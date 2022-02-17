<?php 
                $quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
                if(isset($validateuser[0]['cookiestr'])) {
                	$cookieStr = $validateuser[0]['cookiestr'];
                	$cookieArray = explode('|',$cookieStr);
                	$email = $cookieArray[0];
                	$firstname = htmlspecialchars($validateuser[0]["firstname"]);
                	$lastname = htmlspecialchars($validateuser[0]["lastname"]);
                	$mobile = htmlspecialchars($validateuser[0]["mobile"]);
                }else {
                	$email = "";
                	$firstname = "";
                	$lastname = "";
                	$mobile = "";
                }
				$tempJsArray = array('myShiksha','user');   
                
		$headerComponents = array(
                                                'css'   =>      array('campus-connect-mkt'),
                      							 'js' => array('common','facebook','ajax-api','imageUpload','campusAmbassadorMarketingPage','CalendarPopup','onlinetooltip'),
                                                'jsFooter'=>    $tempJsArray,
                                                'title' =>      'Campus Representatives from Institutes - Shiksha.com',
                                                'product'       =>'campusAmbassadorMarketing',
                                                'showBottomMargin' => false,
						'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'metaDescription' => 'Find the list of Campus Representatives from various Institutes & colleges at CampusConnect Shiksha. Seek complete information about admission processes, fees structure, eligibility, application forms, important dates, and much more.',
				
                                        );
                $this->load->view('common/header', $headerComponents);


//$this->load->view('CA/autoSuggestorForInstitute');  
$this->load->view('common/calendardiv');
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>
<div class="campus-wrapper">
  		<div class="intro-box">
        	<div class="intro-rep-box">
   	 			<div class="intro-image"></div>
    			<div class="intro-content"><i class="campus-sprite comma-open"></i><p>Hi! Preparing for college admissions? Make sure you select the right institute. To do that you need to ask the right questions to the right people. And shiksha.com is just the right place to get your answers in a click. Shiksha Campus representatives, who are current students, alumni and college officials from hundreds of leading institutes are especially here to help you with all the information you need about an institute. Go ahead - Post your questions to Shiksha Campus Rep!</p><i class="campus-sprite comma-close"></i></div>
      			<div class="podium-image"></div>
                <div class="clear"></div>
   		    </div>
            <div class="search-box-bg">
            <i class="campus-sprite curve-image"></i>
                <div class="search-box">
                    <i class="campus-sprite search-icon"></i>
                    <input type="text" class="search-textfield" value="<?php if(!empty($searchInsName)){ echo $searchInsName;}else{ ?>Search by College Name<?php } ?>" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" name="keywordSuggest" id="keywordSuggest"  autocomplete="off" default="Search by College Name" />
                    <div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;z-index:1"></div>
		    <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
                 </div>
             </div>
        	<div class="clear"></div>
  		</div>
		<div id="camp-err-msg" class="camp-err-msg" <?php if($msgFlag!='1'){ ?>style="display: none;"<?php } ?>>
				<p>We currently don't have a Campus Representative from <?php echo $searchInsName;?>.</p>
				<p>You can check out Campus Representatives for other similar colleges below.</p>
		</div>
        <div class="top-rep-box">
        	<h1><i class="campus-sprite star-icon"></i>Top Campus Representatives</h1>
            <ul>
		<?php $i=0;
		foreach($caTopDetailsForMarketingPage['caDetails'] as $key=>$value){
		if($value['badge']=='Official'){$newBadge = 'OFFICIAL';}else if($value['badge']=='CurrentStudent'){$newBadge = 'CURRENT STUDENT';}else{$newBadge = 'ALUMNI';}
		     if($i>2){
		  	break;
		     }
		?>
            	<li>
                    <div class="rep-image"><img alt="<?php echo $value['displayName'];?> Campus Representative of <?php echo $value['insName'];?>" src="<?php if($value['imageURL']!=''){ echo $value['imageURL'];}else{ echo '/public/images/marketingCAImages/default.jpg';}?>" width="99" height="90"/></div>
                    <div class="rep-detail">
                        <a target="_blank" href="<?php echo SHIKSHA_ASK_HOME.'/getUserProfile/'.urlencode($value['dName']); ?>" class="rep-name"><?php echo $value['displayName'];?> </a>
                        <span class="off-btn"><?php echo $newBadge;?></span>
                        <p><strong>Course:</strong> <?php echo $value['courseName'];?></p>
                        <p><strong>Institute:</strong> <?php echo $value['insName'];?></p>
                        <p><i class="campus-sprite ans-icon"></i><?php echo $value['totalAnsCount']; if($value['totalAnsCount']>1){echo " Answers";}else{ echo " Answer";} echo " this week";?></p>
                </div>
                </li>
		<?php
		$i++; } ?>
            </ul>
        </div>	 
        <div class="camp-rep" id="camp-rep-inner">
        	<?php $this->load->view('caMarketingPageInner');?>
        </div>
</div><div class="clearFix"></div> 
<?php
        $this->load->view('common/footer');
?>
