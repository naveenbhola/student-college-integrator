<?php
	$start = 0;
        $count = 10;
	$totalPages = ceil($totalResults/$count);
	$lastPage = $totalResults%$count; //results in last page.
?>

<script language = javascript>

<?php 
if($totalResults<10){
    echo "var doNotMakeAnotherCall = true;";
}
else {
    echo "var doNotMakeAnotherCall = false;";
}
?>

var pageNumberExpected = <?php echo ($currentPage+2);?>;
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
			if(data=='noresults'){	//Show No result message and stop auto pagination					
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
    
     $('#searchResults').scrollPagination({
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
		$.fn.scrollPagination.defaults.contentPage = '<?=$paginationURL?>-'+pageNumberExpected+'-0';
		pageNumberExpected++;              
		doNotMakeAnotherCall = false;
         }
     });
});           
</script>



<!-- Show the Institutes list -->
<div id="searchResults" style="display: none;" data-enhance="false">
	<?=$resultsList?>
</div>

<!-- Loading Div -->
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>

<!-- No more results page -->
<?php if($totalResults<10){ ?>
<nav id="no-result" class="clearfix"><p>No more results</p></nav>
<?php }else{ ?>
<nav id="no-result" class="clearfix" style="display: none;"><p>No more results</p></nav>
<?php } ?>
