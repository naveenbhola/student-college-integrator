<?php GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser;

echo includeJSFiles('mSAComJQ', 'abroadMobile');
?>

<script>
var isUserLoggedIn = <?php if($validateuser!=='false') { echo 'true'; } else { echo 'false'; }  ?> ;
var isStudyAbroadPage = 1;
var isUserComplete= <?=($loggedInUserData['isLocation']>0 && $loggedInUserData['desiredCourse']!= null?'true':'false')?>;
base_url="<?php echo SHIKSHA_HOME;?>";
shiksha_site_current_url="<?php echo $shiksha_site_current_url; ?>";
shiksha_site_current_refferal="<?php echo $shiksha_site_current_refferal; ?>";
logged_in_userid="<?php echo $logged_in_userid;?>";
COOKIEDOMAIN = '<?=COOKIEDOMAIN?>';
var a,b,c,d,e,f,g,h,i;
var bsbParams, loggedInUser;
function loadPageLoadDependency(str,clearIdentifier)
{
    try{
        clearInterval(clearIdentifier);
		window[str]();
    }catch(ex){

    }
}

    $j(window).on('load',function(){
	    <?php
		$asyncJsList = array("commonSA");
	    if(!($dontLoadRegistrationJS == true)){
	    	if(DEBUG_ON  == true){
	    	    if(!is_array($js))
                {
                    $js = array();
                }
                array_push($js, "jquery-ui.min");
                array_push($js, "jquery.ui.touch-punch");
	    		array_push($js,"userRegistration");
	    		array_push($js,"ajax-api");
	    		array_push($js,"commonSA");
	    		array_push($js,"registrationSA");
	    		array_push($js,"jquery.lazy.min");
	    	}
	    	else{
			    $comRegSrcString = includeJSFiles('asyncMSAComReg', 'abroadMobile');		    
				$count = preg_match('/src=(["\'])(.*?)\1/', $comRegSrcString, $comRegSrc);
				?>	    		
		    	var head = document.getElementsByTagName('head').item(0);
			    var script = document.createElement('script');
			    script.setAttribute('type', 'text/javascript');
			    script.setAttribute('src', '<?php echo $comRegSrc[2]; ?>');
			    head.appendChild(script);
		
		<?php 
	    	}
			$asyncJsList[] = "registrationSA";
		
		}
		else {
			if(DEBUG_ON  == true){
				array_push($js, "jquery-ui.min");
                array_push($js, "jquery.ui.touch-punch");	    		
	    		array_push($js,"commonSA");
	    		array_push($js,"jquery.lazy.min");
	    	}else{
				$comSrcString = includeJSFiles('asyncMSACom', 'abroadMobile');;
				$count = preg_match('/src=(["\'])(.*?)\1/', $comSrcString, $comSrc);
				?>
				var head = document.getElementsByTagName('head').item(0);
			    var script = document.createElement('script');
			    script.setAttribute('type', 'text/javascript');
			    script.setAttribute('src', '<?php echo $comSrc[2]; ?>');
			    head.appendChild(script);	    		
		<?php } 
	    }

		if(count($asyncJs)>0){
			$asyncJsList = array_merge($asyncJsList,$asyncJs);
		}
		if(count($jsRequiredInHeader)>0){
			$js = array_diff($js, $jsRequiredInHeader);

		}
		if(!empty($js) && count($js)>0) {
			$js = array_reverse($js);
			foreach($js as $jsFile) {?>
				var head = document.getElementsByTagName('head').item(0);
				var script = document.createElement('script');
				script.setAttribute('type', 'text/javascript');
				<?php  
					$jsPath = '//'.JSURL.'/public/mobileSA/js/'.getJSWithVersion($jsFile,'abroadMobile');
					if(DEBUG_ON == true && in_array($jsFile, $responsiveJSFile)){
						$jsPath = '//'.JSURL.'/public/responsiveAssets/js/'.getJSWithVersion($jsFile,'responsiveAssets');
					}
				?>				
				script.setAttribute('src', '<?=$jsPath;?>');
				head.appendChild(script);
			<?php
			}
		}
		?>

		var timer = 1000;
		a  = setInterval('loadPageLoadDependency("mobileSACommonBinding",a);',timer);
		b  = setInterval('loadPageLoadDependency("initializeGnbAndFooterAccordion",b);',timer);
		<?php if(!($hideRightMenu == true)){ ?>
			c  = setInterval('loadPageLoadDependency("getShortlistCourseCount",c);',timer);
		<?php }?>

		d  = setInterval('loadPageLoadDependency("bindClickHandlerToHideLayer",d);',timer);
		e  = setInterval('loadPageLoadDependency("windowLoadDone",e);',timer);
		<?php
		if(isset($_COOKIE['examPopup']) && $_COOKIE['examPopup'] == '1'){
		?>
		if(isUserLoggedIn == true){
			h  = setInterval('loadPageLoadDependency("checkIfExamScoreUpdateLayerToBeShown",h);',timer);
		}
		<?php
		}
		if(!($hideRightMenu == true)){ ?>
			if(isUserLoggedIn == true){
				g  = setInterval('loadPageLoadDependency("getNewNotificationCount",g);',timer);
			}
		<?php }
		if(is_null($skipBSB) || $skipBSB === false){
			$bsbData = Modules::run('commonModule/BSB/getBSBDataAvailableForPage', $beaconTrackData['pageIdentifier']);
		}
		if(!empty($bsbData)){
		?>
			loggedInUser = '<?php echo (isset($validateuser[0]['userid'])) ? $validateuser[0]['userid'] : 0?>';
			bsbParams = '<?php echo json_encode($bsbData); ?>';
			i  = setInterval('loadPageLoadDependency("initiateBSB",i);',timer);
		<?php
		}
		?>

		<?php if($loadLazyJSFile == true){?>
			f  = setInterval('loadPageLoadDependency("lazyLoadingImages",f);',timer);
		<?php }?>

		var firstJS  = document.getElementsByTagName('script')[0];
		var head  = document.getElementsByTagName('head')[0];
		<?php
        if($deferCSS !== true){
            if($cssBundleMobile != '')
            {
                ?>
                var linkCom = '<?php echo includeCSSFiles('sa-com-mobile', 'abroadMobile', array('crossorigin'));?>';
                head.insertBefore(linkCom, firstJS);
                var link = '<?php echo includeCSSFiles($cssBundleMobile, 'abroadMobile', array('crossorigin'));?>';
                head.insertBefore(link, firstJS);
                <?php
            }
            else
            {
            ?>
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.type = 'text/css';
                link.href = '//<?php echo CSSURL; ?>/public/mobileSA/css/<?php echo getCSSWithVersion("commonSA", "abroadMobile"); ?>';
                link.media = 'all';
                //head.appendChild(link);
                head.insertBefore(link, firstJS);

                <?php if(!is_array($doNotLoadCSS) || is_array($doNotLoadCSS) && !in_array('jquery.mobile-1.4.5', $doNotLoadCSS)){?>
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.type = 'text/css';
                link.href = '//<?php echo CSSURL; ?>/public/mobileSA/css/<?php echo getCSSWithVersion("jquery.mobile-1.4.5", "abroadMobile"); ?>';
                link.media = 'all';
                //head.appendChild(link);
                head.insertBefore(link, firstJS);

                <?php
                }
                if(!is_array($doNotLoadCSS) || is_array($doNotLoadCSS) && !in_array('jquery-ui.min', $doNotLoadCSS) || !($dontLoadJQueryUIMin == true)){?>
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.type = 'text/css';
                link.href = '//<?php echo CSSURL; ?>/public/mobileSA/css/vendor/jqueryUiSliderDatepicker/jquery-ui.min.css';
                link.media = 'all';
                //head.appendChild(link);
                head.insertBefore(link, firstJS);
                <?php     // end : if($deferCSS !== true)
                }?>

                <?php
                if(!empty($css) && count($css) > 0) {
                    foreach($css as $cssFile) {
                    ?>
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.type = 'text/css';
                    link.href = '//<?php echo CSSURL; ?>/public/mobileSA/css/<?php echo getCSSWithVersion($cssFile, "abroadMobile"); ?>';
                    link.media = 'all';
                    //head.appendChild(link);
                    head.insertBefore(link, firstJS);
                    <?php
                    }
                }
            }
        }
		?>
		<?php

		if(!($hideRightMenu == true)){
		?>
			if(isUserLoggedIn == true){
				var loginCSS  = document.createElement('style');
				//loginCSS.innerHTML = '#loggedin .ui-panel-inner{padding:0 !important}.notification-list li p.newNotification{font-weight:bold;}.notification-list li p.oldNotification{font-weight:normal;}';
				loginCSS.innerHTML = '#loggedin .ui-panel-inner{padding:0 !important}';
				head.appendChild(loginCSS);
			}
		<?php
		}
		?>
	});
</script>
