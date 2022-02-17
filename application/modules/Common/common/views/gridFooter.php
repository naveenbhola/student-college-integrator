		<!-- footer html starts -->
		<!-- footer html ends -->
		
		<!-- load js to be loaded in footer -->
		<?php if(isset($jsFooter) && is_array($jsFooter)) {
			$jsFooter = getJsToInclude($jsFooter);
			foreach($jsFooter as $jsFile) { ?>
			<script language="javascript" src="<?php echo $jsFile;?>"></script>
			<?php }
		} ?>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<?php 
		if(!empty($jsFilePlugins)) {
			foreach($jsFilePlugins as $fileName) { ?> 
			<script src="//<?php echo JSURL; ?>/public/js/<?php echo $fileName;?>.js"></script>
			<?php 	
		} 
	}
	?>

</body>
</html>

<script>
	$j = $.noConflict();
	var cookiename = "compare-global-data";
	if (getCookie(cookiename) !='' && (isCompareEnable == true && typeof(isCompareEnable) !='undefined')) { // show compare tool view on window load
		$j('#_cmpSticky').show();
	}else{
		$j('#_cmpSticky').hide();
	}

	function trackEventByCategory(eventCategory,eventAction,eventLabel) {
		if(typeof(pageTracker)!='undefined' && typeof(eventCategory)!='undefined' && eventCategory!=null) {
			pageTracker._trackEvent(eventCategory, eventAction, eventLabel);
		}
		return true;
	}

// New GNB
//to calculate menu-level-1 height
function setheight(_p){
	var a1 = $j('.global-wrapper .active .g_lev2').outerHeight();
	var _a2 = $j(_p).find('.g_lev2');
	var a3 = $j(_p).find('.activee').find('.submenu2');
	var heightSubmenu = $j(_p).find('.activee').find('.submenu2').outerHeight();
	var a4=$j(_p).find('.activee').find('.submenu2 .lastTrTd td');
	$j(a4).css('height',0);
	(a1>heightSubmenu)? $j(a3).css('height',a1) : $j(_a2).css('height',heightSubmenu);
	
	heightSubmenu = $j(a3).height();
	a1 = $j('.global-wrapper .active .g_lev2').height();
	var heightCal =0;
	$j(_p).find('.submenu2 tr').each(function(){
		heightCal += $j(this).height();
	});
	if(heightCal!=0){
		var lastHeight = heightSubmenu-heightCal-9;
		$j(a4).css('height',lastHeight);}
	}

	$j('body').on('mouseover', function(e){
		if($j(e.target).hasClass('submenu') /*|| $j(e.target).hasClass('g_lev2')*/) {
			$j(".menu-overlay , .shiksha-navCut").hide();
			$j(".g_lev1").removeClass('active');
			if($j('#_globalNav').length>0){$j('#_globalNav').css('z-index',99);$j('.menu-overlay').css('z-index',98);}
		}
	});

	$j(function(){

//hover on root menu link
$j('.global-wrapper .g_lev1').hover(
	function(e) {
		setheight(this);
		if($j('#multiLocationLayer').length>0){dissolveOverlayHackForIE();$j('#multiLocationLayer').hide();}
		if($j('#suggestion_output_container').length>0){$j('#suggestion_output_container').hide();}
		if($j('#_globalNav').length>0){$j('#_globalNav,header').css('z-index',9999);$j('.menu-overlay').css('z-index',999);}
		var cutLeft = $j(this).offset().left+(parseInt($j(this).width())/2);
		$j('.shiksha-navCut').show().css('left',cutLeft-8);
	},
	function(e) {
		var t = this;
		$j(t).removeClass('active');
		$j('.menu-overlay, .shiksha-navCut').hide();
		if($j('#_globalNav').length>0){$j('header').css('z-index',100);$j('#_globalNav').css('z-index',99);$j('.menu-overlay').css('z-index',98);}
	});

$j('.g_lev1 >a').hover(
	function(e) {
		var t = $j(this).parent();
		$j('.menu-overlay').show().css('height',$j('#content-wrapper').height());
		$j(t).addClass('active');
		$j(t).find('ul.g_lev2 li').first().addClass('activee');
		setheight($j(this).parent());
	}
	);
//hover on menu-levl-1
$j('.submenu .g_lev2 > li').hover(
	function(e) {
		var t = this;
		$j('.g_lev2 li').removeClass('activee');
		$j(t).addClass('activee');
		setheight($j(this).parent().parent().parent());
	},
	function(e) {
		var t = this;
		$j(t).removeClass('activee');
	});
});
$j('.n-loginSgnup2').click(function(){
	var profileCont=$j('.n-profileBx');
	if (profileCont.hasClass('slideOpen')) 
		$j(profileCont).removeClass('slideOpen').addClass('slideClose');
	else
		$j(profileCont).removeClass('slideClose').addClass('slideOpen');
});
var gnbHeight = 36;
$j(window).scroll(function(){
	var ftrh = '1000';
	if ($j('#_globalNav').length>0  && (typeof(isShowGlobalNav) !='undefined' && isShowGlobalNav)) {
		if($j('#suggestion_output_container').length>0){$j('#suggestion_output_container').hide();}
		if(!($j('.n-headSearch').hasClass('navSrchActv'))) {
			$j('#_globalNav').css('z-index',99);$j('.menu-overlay').css('z-index',98);
		}
		else {
			
		}
		var scrollHeight = $j(this).scrollTop();
		
		if(scrollHeight >= gnbHeight && scrollHeight < (ftrh - 100)) {
			$j('.menu-overlay').css('top','48px');
			if(!($j('.n-headSearch').hasClass('navSrchActv'))) {
				$j('header').css('z-index',100);
			}
			$j('#_globalNav').addClass('_gnb-sticky').show();
			$j('.shiksha-navCut').css({'top':'40px'});
		}else if(scrollHeight > (ftrh - 100)){
			$j('.menu-overlay').css('top','81px');
			if(!($j('.n-headSearch').hasClass('navSrchActv'))) {
				$j('header').css('z-index',99);
			}
			$j('#_globalNav').removeClass('_gnb-sticky').hide();
		}else{
			$j('.menu-overlay').css('top','81px');
			if(!($j('.n-headSearch').hasClass('navSrchActv'))) {
				$j('header').css('z-index',99);
			}
			$j('#_globalNav').removeClass('_gnb-sticky').show();
			$j('.shiksha-navCut').css({'top':'74px'});
		}
	}


	if ($j('#_globalNav').length>0  && (typeof(isShowGlobalNav) !='undefined' && !isShowGlobalNav)) {
		var scrollHeight = $j(this).scrollTop();
		var gnbLevelHeight = $j('.g_lev1.active .submenu').height(); 

		if(scrollHeight >= gnbHeight && scrollHeight >= gnbLevelHeight) {
			$j('#_globalNav ul li').each(function(){
				if ($j(this).hasClass('active')) {
					$j(this).removeClass('active');
					$j('ul.g_lev2 li').removeClass('activee');
				}

			});

			$j('.g_lev2 ul li').each(function(){
				if ($j(this).hasClass('activee')) {
					$j(this).removeClass('activee');
				}	
			});
			$j(".menu-overlay").hide();
		}
	}
});
	</script>