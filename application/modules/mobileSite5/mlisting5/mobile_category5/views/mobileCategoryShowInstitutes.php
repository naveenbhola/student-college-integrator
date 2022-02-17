<?php
	$totalResults = $categoryPage->getTotalNumberOfInstitutes();
	$currentPage = $request->getPageNumberForPagination();
	$totalPages = ceil($totalResults/$mobile_website_pagination_count);
	$lastPage = $totalResults%$mobile_website_pagination_count; //results in last page.
	$rnrURLSecondHalf 	= "";
	$rnrURLFirstHalf 	= "";
	if($categoryPageTypeFlag == CP_NEW_RNR_URL_TYPE && in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
		$urlRequest = clone $request;
		$urlRequest->setNewURLFlag(1);
		$urlFetch = $urlRequest->getURL($currentPage+1);
		$split = explode("ctpg", $urlFetch);
		$splitFirstHalf = explode("-", $split[0]);
		$rnrURLSecondHalf = "ctpg".$split[1];
		$splitFirstHalfLength = count($splitFirstHalf);
		$currentRNRPageNo = 0;
		$endIndex = -1;
		for($i = $splitFirstHalfLength - 1; $i >=0 ; $i--){
			if(!empty($splitFirstHalf[$i]) && is_numeric($splitFirstHalf[$i])){
				$currentRNRPageNo = $splitFirstHalf[$i];
				$endIndex = $i;
			}
		}
		$new = array();
		for($i = 0; $i < $endIndex ; $i++){
			$new[] = $splitFirstHalf[$i];
		}
		if(!empty($new)){
			$rnrURLFirstHalf = implode("-", $new);
		}
	} else {
		$urlRequest = clone $request;
		$urlFetch = $urlRequest->getURL($currentPage+1);
		$urlArray = explode('-',$urlFetch);
		for($i=0;$i<(count($urlArray)-2);$i++){
			$paginationURL .= ($paginationURL=='')?$urlArray[$i]:'-'.$urlArray[$i];
		}
	}
?>

<script language = javascript>
<?php 
if($totalResults<10){echo "var doNotMakeAnotherCall = true;";}
else {echo "var doNotMakeAnotherCall = false;";} ?>
var pageNumberExpected = <?php echo ($currentPage+2);?>;
var checkForSize= true;	
var noResultFound = false;
var categoryPageType = '<?php echo $categoryPageTypeFlag;?>';
var rnrURLFirstHalf = '<?php echo $rnrURLFirstHalf;?>';
var rnrURLSecondHalf = '<?php echo $rnrURLSecondHalf;?>';

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
			if(data.trim()=='noresults'){	//Show No result message and stop auto pagination					
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
			  if(categoryPageType == "RNRURL"){
					var URLFirstHalf = '<?=$rnrURLFirstHalf?>';
                  	var URLSecondHalf = '<?=$rnrURLSecondHalf?>';
                  	$.fn.scrollPagination.defaults.contentPage =  URLFirstHalf.concat('-',pageNumberExpected,'-',URLSecondHalf);
					
			  } else {
				   	$.fn.scrollPagination.defaults.contentPage = '<?=$paginationURL?>-'+pageNumberExpected+'-0';
			  }
              pageNumberExpected++;              
              doNotMakeAnotherCall = false;
              if (pageNumberExpected > <?php echo ($currentPage+$this->config->item('page_count_per_category_page'));?> && checkForSize){ // if more than 100 results already loaded, then stop pagination (only for testing)
		 $('#paging').show();
                 $('#content').stopScrollPagination();
	         doNotMakeAnotherCall = true;
              }
         }
     });
});           
</script>

<!-- Show the Institutes list -->
<div id="contentCategory" data-enhance="false">
	<?php
	if( isset($tracking_keyid) ) { // Pass on the tracking key to the view ahead if it is set
		$this->load->view('mobileCategoryListings', array('tracking_keyid'=>$tracking_keyid));
	} else {
		$this->load->view('mobileCategoryListings',array('tracking_keyid' => MOBILE_NL_CTPG_TUPLE_DEB));
	}
	?>
</div>

<!-- Loading Div -->
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>

<!-- Display the Pagination links at the end of 10 scrolled pages -->
<nav id="paging" class="clearfix" <?php /*if(!isset($_GET['noscript'])) {?> style="display:block;" <?php }else{ */?>  style="display:none;" <?php /*} */?>>
<?php 
$dataForPagination['currentPage'] =  $currentPage;
$dataForPagination['urlRequest'] =  $urlRequest;
$dataForPagination['totalPages'] =  $totalPages;
/*if(!isset($_GET['noscript'])) { */?>
	<noscript>
	<?php $this->load->view('paginationForDisabledJS', $dataForPagination); ?>
	</noscript>
<?php /*} else { */?>
	<?php if($currentPage>10){	//This is the Next page
	echo '<a class="prev" href="'.$urlRequest->getURL($currentPage-10).'"><i class="icon-prev"></i> Previous</a>';
	}
if($totalPages>($currentPage+10)){
	echo '<a class="next" href="'.$urlRequest->getURL($currentPage+10).'">Next <i class="icon-next"></i></a>';	
	}
/*}*/?>
</nav>

<!-- No more results page -->
<?php if($totalResults<10){ ?>
<nav id="no-result" class="clearfix"><p>No more results</p></nav>
<?php }else{ ?>
<nav id="no-result" class="clearfix" style="display: none;"><p>No more results</p></nav>
<?php } ?>
