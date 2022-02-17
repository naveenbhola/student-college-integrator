<?php
/*	if(strtolower($product) == 'ranking'){?>
				<div class="head-advrtsmnt" style="display: block;">
        		<div class="advrtsmntBx">
				<?php
					if(!empty($rankingBannerProperties)){
					$this->load->view('common/banner', $rankingBannerProperties);
				} ?>
        </div></div>


		<?php }	else */
		//if(in_array(strtolower($product), array('articles', 'forums', 'careerproduct', 'exampage', 'rnrcategorypage', 'ranking','category', 'categoryheader', 'mba', 'articlesd','gradheader','testprep','anadesktopv2')) && !in_array($bannerProperties['pageId'], array('DISCUSSION_DETAIL','TAGS','EXAM'))){ 
		if(in_array(strtolower($product), array('category')) && !in_array($bannerProperties['pageId'], array('DISCUSSION_DETAIL','TAGS','EXAM','CATEGORY'))){
			?>

				<div class="head-advrtsmnt" style="display: block;">
        		<div class="advrtsmntBx">
        		<?php
						$bannerProperties = isset($bannerProperties) ? $bannerProperties : array('pageId'=>'SEARCH', 'pageZone'=>'HEADER');
							$this->load->view('common/banner',$bannerProperties);
				?>
				</div></div>

			<?php } /*else if( in_array($bannerProperties['pageId'] , array('EXAM','ALL_EXAM')) && $isBTechExam == "true" && false){
						?>
				<div class="head-advrtsmnt" style="display: block;">
        		<div class="advrtsmntBx">
       			 <?php
						$bannerProperties = isset($bannerProperties) ? $bannerProperties : array('pageId'=>'SEARCH', 'pageZone'=>'HEADER');
							$this->load->view('common/banner',$bannerProperties);
				?>
				</div></div>

			<?php }*/

if(is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])) {
	$userEmail  = substr($validateuser[0]['cookiestr'], 0, strpos($validateuser[0]['cookiestr'], '|'));
	$name       = $validateuser[0]['firstname'].' '.$validateuser[0]['lastname'];
	$name       = (strlen($name) < 15) ? $name : ((strlen($validateuser[0]['firstname']) > 15) ? substr($validateuser[0]['firstname'],0,13).'..' : $validateuser[0]['firstname']);
	$userName   = ucwords($name);
	if($validateuser[0]['avtarurl']){
		$displayPic = addingDomainNameToUrl(array('url' => $validateuser[0]['avtarurl'] , 'domainName' =>MEDIA_SERVER));	
		$displayPic = getImageUrlBySize($displayPic,"small");		
	}
}?>
<header>
<?php if($widgetForPage != 'HOMEPAGE_DESKTOP' && $product != 'shiksha_analytics') { 
	$class = 'n-headerP innerpage-header'; 
} else {
	$class = 'n-headerP';
} ?>
    	<div class="<?php echo $class; ?>" id="_globalNav">
		<div class="shiksha-navCut"></div>
	        <div class="n-header">
	            <div class="n-logo">
	                <a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com">
	                	<i class="icons ic_logo ic_logo_prefix"></i>				<i class="icons ic_logo"></i></a>
	            </div>
			<?php
 				$this->load->view('common/newGNB');
			?>
	        <div class="n-lognSgnBx">

	        <span id="shareIcon" class="share-shiksha" data-position="header">
				<a ga-page="gnb" ga-attr="GNB_OPEN_STATE" ga-optlabel="Share_GNB">
					<i class="icons ic_social-share"></i>&nbsp;
				</a>
        	</span>

			<span class="ask-shiksha"><a href="<?=SHIKSHA_ASK_HOME?>" ga-page="gnb" ga-attr="GNB_OPEN_STATE" ga-optlabel="ASK_GNB" title="Get answers from current students, alumni & our experts"><i class="icons ic_ask-shiksha"></i>Ask</a></span>
			<?php if(empty($userEmail) || $userEmail == ''){?>
	        	<span class="n-loginSgnup">
					<i class="blank-pp-icon icons"></i>
	        		<a href="javascript:void(0);" action="login">Login</a> 
	        		<span class="registerPipe">|</span>
	        		<a href="javascript:void(0);">Sign Up</a>
	        	</span>
			<?php }else{?>
        		<span class="n-headShortls nShorlstd" id="_defaultShortlist">
					<a href="<?php echo SHIKSHA_HOME;?>/resources/colleges-shortlisting#myshortlist" title="Shortlist college"><i class="icons ic_head_shorlist1x"></i>Shortlist</a>
				</span>
				<span class="n-headShortlst nShorlstd" id="_myShortlisted" style="display: none;">	
					<a href="<?php echo SHIKSHA_HOME;?>/resources/colleges-shortlisting#myshortlist" title="Shortlisted colleges"><i class="icons ic_head_shorlisted1x" id="selectedIcons"></i><p id="myShortlistCount"></p>Shortlist</a>
				</span>
            	<span class="n-loginSgnup2">
					<span>
						<?php if($validateuser[0]['avtarurl']){ ?>
				    	<img class="small-pp-img" src="<?=$displayPic?>">
				    	<?php } else { ?>
				    		<p class="user_initial"><?=$userName[0];?></p>
				    	 <?php } ?>
				    </span>
            		<a class="n-username"><?php echo $userName; ?></a>
            		<a class="n-username-icon"><i class="icons ic_profilepage"></i></a>
					<div class="n-profileBx <?php if($product != 'home' && $product != 'shiksha_analytics'){ echo 'innerp-log-box';}?>" >
	            		<a href="<?php echo SHIKSHA_HOME.'/userprofile/edit';?>" tabindex="1">Profile</a>
	            		<a id="predictorList" style="display: none;" href="">Predictor List</a>
	            		<a href="javascript:void(0);" tabindex="2" action="logout">Sign Out</a>
	            	</div>
            	</span>
			<?php }?>
	        </div>
			<p class="clr"></p>
	        </div>
			<div class="searchBtnOvrly"></div>
	    </div>
    </header>
<div class="menu-overlay"></div>
<?php if($widgetForPage == 'HOMEPAGE_DESKTOP') { ?>
<?php echo Modules::run('common/GlobalShiksha/getHeaderSearch'); ?>
<?php } ?>
