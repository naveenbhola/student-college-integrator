<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>" type="text/javascript"></script>
<script type="text/javascript">
	$j(document).ready(function($j) {
                       if(STUDY_ABROAD_TRACKING_KEYWORD_PREFIX == "" && $j(window).width() > 800) {
                               floatOtherCatPageWidgets(); // For only India Cat pages (and above screen width 800), we need to float Latest news and BMS Banner widget.
                       }

			if(getCookie("comparelayer"+$categorypage.key) == 1){
				startingSlide = 1;
				flagCompare = 1;
				recommendation_json_i = getCookie('recommendation_json_i');
				recommendation_json_c = getCookie('recommendation_json_c');
				thanksHTML = getCookie('thanks_you_text'+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX);
				if(getCookie('compare_bottom_widget') !== false){
					$j("#thanks-box").show();
				}
				if(recommendation_json_c !== false){
					$('confirmation-box-wrapper').innerHTML = thanksHTML;
				}
			}else{
				startingSlide = 0;
				flagCompare = 0;
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
						if(typeof showCoursePagesFloatingBar !== 'undefined' && showCoursePagesFloatingBar == 1 ) {
							scrollCoursePagesHeader();
						}
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
							var height = 70;
							if(studyAbroad == 1 && flagCompare == 1){
								height = 1500;
								flagCompare = 0;
							}
							$j('#mainWrapper').height($j('#compare-cont').height()+height);
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
                var pageHeight = Math.max($j(document).height(), $j(window).height());
                var toTopDivShowPos = parseInt((pageHeight)* (0.60));
                
		$j(window).scroll(function() {
                    
                        // console.debug("Window height = "+pageHeight+", scroll top = "+$j(this).scrollTop()+", Check pos = "+toTopDivShowPos);
			// if(($j(this).scrollTop() >= $j('#page-top').offset().top+250) && (compareSlide == 0 )
                        if(($j(this).scrollTop() >= toTopDivShowPos) && (compareSlide == 0 )
			   && !(/MSIE ((5\\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32")) {
				if($j(window).width() < 1000){
					$j('#toTop').css('left',($j('#page-top').offset().left+818) + "px");
				}else{
					$j('#toTop').css('left',($j('#page-top').offset().left+925) + "px");
				}
				$j('#toTop').fadeIn();
			} else {
				$j('#toTop').fadeOut();
			}
		});
	 
		$j('#toTop').click(function() {
			$j('body,html').animate({scrollTop:$j('#cateSearchBlock').offset().top},(f_scrollTop()/6));
		});	
	});

</script>
<div id="toTop" uniqueattr="CategoryPage/backtoFilters" >&#9650; Back to Top</div>
