<?php
    //if($searchurlparams['start']>=0 && $searchurlparams['start'] < $max_offset && $total_results>$result_limit):
    $temp_url = $searchurlparams;
    $temp_url['start'] = $temp_url['start'] + $result_limit;
    $urlFetch = urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/search/index',$temp_url));
    //endif;
    $currentPage = 1;
    $urlArray = explode('start=',$urlFetch);
    $paginationURLPrev = $urlArray[0];
    $urlArray = explode('&',$urlArray[1]);
    for($i=1;$i<(count($urlArray));$i++){
        $paginationURLNext .= ($paginationURLNext=='')?$urlArray[$i]:'&'.$urlArray[$i];
    }
    
?>
    <script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile");?>";></script>
<script language = javascript>
var doNotMakeAnotherCall = false;
var pageNumberExpected = <?php echo (($temp_url['start']-$result_limit)+($result_limit*2));?>;
var checkForSize= true;	
var noResultFound = false;

(function( $ ){
$.fn.scrollPagination = function(options) {
	var opts = $.extend($.fn.scrollPagination.defaults, options);  
	var target = opts.scrollTarget;
	if (target == null){
		target = obj; 
 	}
	opts.scrollTarget = target; 
	return this.each(function() {
	  $.fn.scrollPagination.init($(this), opts);
	});
};
  
$.fn.stopScrollPagination = function(){
  return this.each(function() {
 	$(this).attr('scrollPagination', 'disabled');
  });
};
  
$.fn.scrollPagination.loadContent = function(obj, opts){
 var target = opts.scrollTarget;
 var mayLoadContent = $(target).scrollTop()+opts.heightOffset >= $(document).height() - $(target).height();
 if (mayLoadContent && !doNotMakeAnotherCall && !noResultFound && (!hamburgerFlag && typeof(hamburgerFlag) !='undefined')){
         doNotMakeAnotherCall = true;
	 if (opts.beforeLoad != null){
		opts.beforeLoad(); 
	 }
	 $(obj).children().attr('rel', 'loaded');
	 $.ajax({
		  type: 'POST',
		  url: opts.contentPage,
		  data: opts.contentData,
		  success: function(data){
			if(data=='noresults' || data.length<15){	//Show No result message and stop auto pagination					
				$('#no-result').show();
				$('#content').stopScrollPagination();
				doNotMakeAnotherCall = true;
				noResultFound = true;
				checkForSize = false;
			}
			else{
				$(obj).append(data);
			}
			var objectsRendered = $(obj).children('[rel!=loaded]');			
			if (opts.afterLoad != null){
				opts.afterLoad(objectsRendered);	
			}
		  },
		  dataType: 'html'
	 });
 }
};	
  
$.fn.scrollPagination.init = function(obj, opts){
 var target = opts.scrollTarget;
 $(obj).attr('scrollPagination', 'enabled');	
 $(target).scroll(function(event){
	if ($(obj).attr('scrollPagination') == 'enabled'){
 		$.fn.scrollPagination.loadContent(obj, opts);		
	}
	else {
		event.stopPropagation();	
	}
 });
 $.fn.scrollPagination.loadContent(obj, opts);
};
	
$.fn.scrollPagination.defaults = {
   	 'contentPage' : null,
     	 'contentData' : {},
	 'beforeLoad': null,
	 'afterLoad': null	,
	 'scrollTarget': null,
	 'heightOffset': 0		  
};	
})( jQuery );

$(function(){
     $.fn.fadeInWithDelay = function(){
         var delay = 0;
         return this.each(function(){
             $(this).delay(delay).animate({opacity:1}, 200);
             delay += 100;
         });
     };
	
     $('#contentCategory').scrollPagination({
         'contentPage': '<?=$urlFetch?>',
         'contentData': ({type: 'fetchAjax'}),
         'scrollTarget': $(window),
         'heightOffset': <?=$this->config->item('height_for_auto_pagination_on_category_page')?>,
         'beforeLoad': function(){
             $('#loading').fadeIn();
         },
         'afterLoad': function(elementsLoaded){
              $('#loading').fadeOut();
              reAdjustRecoLayer();
              var i = 0;
              $(elementsLoaded).fadeInWithDelay();
              $.fn.scrollPagination.defaults.contentPage = '<?=$paginationURLPrev.'start='?>'+pageNumberExpected+'<?='&'.$paginationURLNext?>';
              pageNumberExpected+=<?=$result_limit?>;              
              doNotMakeAnotherCall = false;
              if (pageNumberExpected > <?php echo ($result_limit*10)+($temp_url['start']-$result_limit);?> && checkForSize){ // if more than 100 results already loaded, then stop pagination (only for testing)
		 $('#paging').show();
                 $('#content').stopScrollPagination();
	         doNotMakeAnotherCall = true;
              }
         }
     });
});           
</script>

<?php
if (getTempUserData('confirmation_message')){?>
<section class="top-msg-row">
        <div class="thnx-msg">
            <i class="icon-tick"></i>
                <p>
                <?php echo getTempUserData('confirmation_message'); ?>
                </p>
        </div>
</section>
<?php } ?>
<?php
   deleteTempUserData('confirmation_message');
   deleteTempUserData('confirmation_message_ins_page');
?>

<script>var noMoreCall = false;</script>

<?php
    if(empty($solr_institute_data['single_result'])): //Normal search results
    if($solr_institute_data['total_institute_groups'] <= 0):
?>
    <div class="content-wrap" style="margin-top:-5px">
        <section class="content-child clearfix">
            <article id="page-not-found">
                <h3>
                    <i class="icon-404"></i>
                    <p style="line-height:normal; margin:3px 0 0 32px">No institute or course found for <br/><span>&ldquo;<?php echo  displayTextAsPerMobileResolution(htmlspecialchars($solr_institute_data['raw_keyword']),2,true);?>&rdquo;</span></p>
                </h3>
                <p class="not-foundTxt">
                    Try relaxing search criteria<br /><br />Please check your spelling and try some different keywords.</p>
                <input type="button" name="search" value="Modify Search" class="search-btn" onclick="window.location='/search/showSearchHome';">
                
            </article>
         </section>
    </div>    
    <script>$('#sortDiv').hide(); if(!document.getElementById('showSelectedFilters')){ $('#showFilterButton').hide(); }</script>
<?php
    else:
        echo "<div id='contentCategory' data-enhance='false'>";
            $this->load->view("normalsearchsnippet");
        echo "</div>";
	if($solr_institute_data['total_institute_groups'] <= 10){
	    echo "<script>var noMoreCall = true;</script>";
	}
    endif;
    elseif($solr_institute_data['single_result'] == 1):
        echo "<div id='contentCategory' data-enhance='false'>";
        $this->load->view("singleinstituteresult");
	echo "<script>if(!document.getElementById('showSelectedFilters')){ $('#showFilterButton').hide();}</script>";    
        echo "</div>";
    endif;
?>

	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>        

    <input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
    <input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
    
	<div data-role="popup" id="popupBasic" style="background:#EFEFEF;width: 100%; top:10%;" >
		<div id="recomendation_layer_listing" style="margin-top:20px;padding-bottom:20px;"></div>
	</div>        

<!-- Loading Div -->
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>

<!-- Display the Pagination links at the end of 10 scrolled pages -->
<nav id="paging" class="clearfix" style="display:none;">
        <?php if($searchurlparams['start']>=$result_limit):
                $temp_url = $searchurlparams;
                $temp_url['start'] = $temp_url['start'] - ($result_limit*10);
        ?>
        <a class="prev" href="<?php echo urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/search/index',$temp_url));?>"><i class="icon-prev"></i> Previous</a> 
        <?php endif;?>
        <?php if($searchurlparams['start']>=0 && $searchurlparams['start'] < $max_offset && $total_results>$result_limit):
                        $temp_url = $searchurlparams;
                        $temp_url['start'] = $temp_url['start'] + ($result_limit*10);
                        
        ?>
        <a class="next" href="<?php echo urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/search/index',$temp_url));?>">Next <i class="icon-next"></i></a>
        <?php endif;?>
</nav>

<!-- No more results page -->
<nav id="no-result" class="clearfix" style="display: none;"><p>No more results</p></nav>


<?php //if($total_results>0 && $total_results<=$result_limit) echo "<script>$('#no-result').show();</script>";?>

<script>
//If the Number is less than 10, we will stop pagination
if(noMoreCall){
    $('#no-result').show();
    $('#content').stopScrollPagination();
    doNotMakeAnotherCall = true;
    noResultFound = true;
    checkForSize = false;
}

function trackReqEbrochureClick(courseId){
try{
    _gaq.push(['_trackEvent', 'HTML5_Search_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}


var show_recommendation = getCookie('show_recommendation');
var recommendation_course = getCookie('recommendation_course');
var hide_recommendation = getCookie('hide_recommendation');

if(show_recommendation == 'yes' && hide_recommendation != 'yes') {
	$(document).ready(function(){
			var isRankingPage = 'NO';
			var brochureAvailable = 'YES';
			var pageType = 'SEARCH_MOB_Reco_ReqEbrochure';
	 		var screenWidth =  window.jQuery('#screenwidth').val();
			var screenHeight = window.jQuery('#screenheight').val();
            var trackingPageKeyId = '<?php echo $recommendationTrackingPageKeyId?>';
			var urlRec = '/muser5/MobileUser/showRecommendation/'+recommendation_course+'/CP_Reco_popupLayer'+'/0/0/0/'+brochureAvailable+'/'+isRankingPage + '/' + pageType+'/\'\'/0/'+trackingPageKeyId;
			jQuery.ajax({
				url: urlRec,
				type: "POST",
				success: function(result)
				{
	        		   if((result.trim()) != ''){       	
							
							trackEventByGAMobile('HTML5_RECOMMENDATION_SEARCH')
	 			                        setCookie('show_recommendation','no',30);
 			                                setCookie('recommendation_course','no',30);

							$('#recomendation_layer_listing').html(result);							
							$('#popupBasic-popup').css('width',screenWidth);
							$('#popupBasic-popup').css('max-width',screenWidth);

							var window_width = $('#wrapper').width();
							var popup_width = window_width - 5 ;
							
							var top_pos = 10 + $('body').scrollTop() + 'px';
							$('#popupBasic').css({'position':'absolute','z-index':'99999' , 'cursor' : 'pointer' , 'top':top_pos , 'background-color' : '#efefef' , 'margin' : '5px' , 'width' : popup_width });
$('#popupBasic').addClass('ui-popup ui-overlay-shadow ui-corner-all ui-body-c');

//$('#wrapper').css({'background' : '#000' , 'z-index' : '100' , 'opacity' : '0.4'})

							var window_height = $(document).height();
							var window_width = $('#wrapper').width();
$('#popupBasicBack').css({'background' : '#000' , 'opacity' : '0.4' , 'z-index' : '9999' , 'display' : 'block' , 'width'  : window_width , 'height' : window_height , 'position':'absolute'});



							$('#popupBasic').show();
					}
								},
				error: function(e){
				}
			});		            	
	});
}

setCookie('hide_recommendation','no',30);        
setCookie('show_recommendation','no',30);

</script>

