<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>" type="text/javascript"></script>
<script type="text/javascript">
	$j(document).ready(function($j) {
			if(getCookie("comparelayer"+$categorypage.key) == 1){
				startingSlide = 1;
				recommendation_json_i = getCookie('recommendation_json_i');
				recommendation_json_c = getCookie('recommendation_json_c');
				thanksHTML = getCookie('thanks_you_text');
				if(getCookie('compare_bottom_widget') !== false){
					$j("#thanks-box").show();
				}
				if(recommendation_json_c !== false){
					$('confirmation-box-wrapper').innerHTML = thanksHTML;
				}
			}else{
				startingSlide = 0;
			}
			slider = $j('#mainWrapper').bxSlider({
					controls: false,
					//mode:'fade',
					speed:700,
					startingSlide : startingSlide,
					'infiniteLoop':false,
					'hideControlOnEnd':true,
					'onAfterSlide' : function(currentSlideNumber){
						compareSlide = currentSlideNumber;
						if(currentSlideNumber == 0){
							if(typeof(visitedmainPage) == 'undefined'  && startingSlide == 0){
								visitedmainPage = 1;
							}else{
								$j('#mainWrapper').height($j('#mainPage').height()+50);
								$j('#mainWrapper').css('overflow','hidden');
								$j('html,body').animate({scrollTop: remember_scroll},0);
							}
							$j('#compareBlock').show();
							$j('#comparePageTop').hide();
							setCookie("comparelayer"+$categorypage.key,0);
							floatCompareWidgetHandler();
							$j('#compare-cont').css('visibility','hidden');
							$j('#adsense').css('visibility','visible');
						}else{
							$j('#mainWrapper').height($j('#compare-cont').height()+70);
							$j('#mainWrapper').css('overflow','hidden');
							setCookie("comparelayer"+$categorypage.key,1);
							$j('#compareBlock').hide();
							$j('#mainPage').css('visibility','hidden');
							$j('#adsense').css('visibility','hidden');
						}
				}
			});

	});
	$j(function() {
		$j(window).scroll(function() {
			if(($j(this).scrollTop() >= $j('#search-box').offset().top+250) && (compareSlide == 0 )
			   && !(/MSIE ((5\\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32")) {
				if($j(window).width() < 1000){
					$j('#toTop').css('left',($j('#search-box').offset().left+818) + "px");
				}else{
					$j('#toTop').css('left',($j('#search-box').offset().left+925) + "px");
				}
				$j('#toTop').fadeIn();
			} else {
				$j('#toTop').fadeOut();
			}
		});
	 
		$j('#toTop').click(function() {
			$j('body,html').animate({scrollTop:$j('#search-box').offset().top},(f_scrollTop()/6));
		});	
	});

</script>
<div id="toTop" uniqueattr="CategoryPage/backtoFilters" >&#9650; Back to Top</div>
