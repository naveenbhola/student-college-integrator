<?php
    //if($searchurlparams['start']>=0 && $searchurlparams['start'] < $max_offset && $total_results>$result_limit):
    $temp_url = $searchurlparams;
    $temp_url['start'] = $temp_url['start'] + $result_limit;
    $urlFetch = urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/getEB/index',$temp_url));
    //endif;
    $currentPage = 1;
    $urlArray = explode('start=',$urlFetch);
    $paginationURLPrev = $urlArray[0];
    $urlArray = explode('&',$urlArray[1]);
    for($i=1;$i<(count($urlArray));$i++){
        $paginationURLNext .= ($paginationURLNext=='')?$urlArray[$i]:'&'.$urlArray[$i];
    }
?>

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
                    <p style="line-height:normal; margin:3px 0 0 32px">No colleges matched your search. Please check if you have entered the correct name or try a different search.</p>
                </h3>
                <!--<p class="not-foundTxt">
                    Try relaxing search criteria<br /><br />Please check your spelling and try some different keywords.</p>-->
                <input type="button" name="search" value="Modify Search" class="search-btn" onclick="window.location='/getEB/showSearchHome';">
                
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


<!-- Loading Div -->
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>

<!-- Display the Pagination links at the end of 10 scrolled pages -->
<nav id="paging" class="clearfix" style="display:none;">
        <?php if($searchurlparams['start']>=$result_limit):
                $temp_url = $searchurlparams;
                $temp_url['start'] = $temp_url['start'] - ($result_limit*10);
        ?>
        <a class="prev" href="<?php echo urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/getEB/index',$temp_url));?>"><i class="icon-prev"></i> Previous</a> 
        <?php endif;?>
        <?php if($searchurlparams['start']>=0 && $searchurlparams['start'] < $max_offset && $total_results>$result_limit):
                        $temp_url = $searchurlparams;
                        $temp_url['start'] = $temp_url['start'] + ($result_limit*10);
                        
        ?>
        <a class="next" href="<?php echo urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/getEB/index',$temp_url));?>">Next <i class="icon-next"></i></a>
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
    _gaq.push(['_trackEvent', 'HTML5_GET_EB_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}

</script>

