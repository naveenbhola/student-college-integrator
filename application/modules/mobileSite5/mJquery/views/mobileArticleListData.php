 <script>
var articleType = "all";
var categoryPresent = false;
<?php 
		if($totalArticles<10){
			echo "var doNotMakeAnotherCall = true;";
		}
		else {
			echo "var doNotMakeAnotherCall = false;";
		} 
		if($blogType == 'news'){
			echo "var articleType = 'news';";
		} else if($blogType == 'kumkum')
		{
			echo "var articleType = 'kumkum';";
		}
		else if($blogType == 'examPage'){
			echo "var articleType = 'examPage';";
		}

		
		if(!empty($categoryId) && $blogType != 'examPage'){
			echo 'var categoryPresent = true ;';
		}
		
		
?>
		var checkForSize= true;	
		var noResultFound = false;
		var addArticleType = '';
		if(articleType == 'news' || articleType == 'examPage') {
			var pageNumberExpected = <?php echo ($currentPage+2);?>;	 
		}else {
			var pageNumberExpected = <?php echo ($currentPage+1);?>;
		}

		if(<?echo $currentPage; ?> > 10) { 
			var startPageAll = <?php echo ($currentPage-1)*10;?>;
			var startPageNews = <?php echo $currentPage;?>;
			var page = (pageNumberExpected-1)*10;
		}else {
			var startPageAll = <?php echo ($currentPage)*10;?>;
			var startPageNews = <?php echo $currentPage+1;?>;
			var page = pageNumberExpected*10;
		}
		
		if (articleType == 'kumkum') {
		       var addArticleType = '?type='+articleType;
		}
		
		

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

    if(articleType == 'news' || articleType == 'examPage') {
        if(categoryPresent) {
        	var url = '<?=$baseUrl?>-' + startPageNews  + '?category=<?=$categoryId?>';
        }else {
        	var url = '<?=$baseUrl?>-' + startPageNews ;
        }
    }else { 
    	if(categoryPresent && articleType != 'kumkum') {
		
			var url = '<?=$baseUrl?>/'+startPageAll+'/10' + '?category=<?=$categoryId?>';
		
    	}else if(categoryPresent && articleType == 'kumkum'){
	
		        var url = '<?=$baseUrl?>/'+startPageAll+'/10' +addArticleType+ '&category=<?=$categoryId?>';
	
	}else {
		
		        var url = '<?=$baseUrl?>/'+startPageAll+'/10'+addArticleType;
		
    	}

    }  

    $('#contentArticle').scrollPagination({
        'contentPage': url,
        'contentData': ({postType: 'fetchAjax'}),
        'scrollTarget': $(window),
        'heightOffset': <?=$this->config->item('height_for_auto_pagination_on_category_page')?>,
        'beforeLoad': function(){
            $('#loading').fadeIn();
        },
        'afterLoad': function(elementsLoaded){
             $('#loading').fadeOut();
             var i = 0;
             $(elementsLoaded).fadeInWithDelay();
             if(articleType == "all" || articleType == "kumkum" ) {
            	 if(categoryPresent && articleType != "kumkum" ) {
                       
				$.fn.scrollPagination.defaults.contentPage = '<?=$baseUrl?>/'+page+'/10' + '?category=<?=$categoryId?>';
			
            	 }else if(categoryPresent && articleType == "kumkum" ){
				$.fn.scrollPagination.defaults.contentPage = '<?=$baseUrl?>/'+page+'/10' +addArticleType+ '&category=<?=$categoryId?>';
		 
		 }else {
		          
				$.fn.scrollPagination.defaults.contentPage = '<?=$baseUrl?>/'+page+'/10'+addArticleType; 
            	 }
                	 
             }else if(articleType == "news" || articleType == 'examPage') {
            	 if(categoryPresent) {
            	 	$.fn.scrollPagination.defaults.contentPage = '<?=$baseUrl?>-'+pageNumberExpected  + '?category=<?=$categoryId?>';
            	 }else {
            		 $.fn.scrollPagination.defaults.contentPage = '<?=$baseUrl?>-'+pageNumberExpected;
            	 }
             }

             pageNumberExpected++;
	     if(articleType == 'examPage'){
		page = (pageNumberExpected-2)*10;
	     }
             else if(articleType == 'news') {
            	 page = (pageNumberExpected-1)*10;
             }else {
            	 if(<?echo $currentPage; ?> > 10) { 
            	 	page = (pageNumberExpected-1)*10;
            	 }else {
            		 page = pageNumberExpected*10;
            	 }
             }

             doNotMakeAnotherCall = false;

             if(pageNumberExpected > 11 ) {
				 
                 var checkCount = pageNumberExpected-3;
                 var checkCount = checkCount%10;
                 if( (checkCount+2) > 10 ||  (page > <?php echo ( $totalArticles + 10); ?>)
						
                          ){ // if more than 100 results already loaded, then stop pagination (only for testing)
     				 $('#paging').show();
                    $('#content').stopScrollPagination();
    	         	doNotMakeAnotherCall = true;
                 }

                 
                 
             }else {
                 
            	 var checkCount = pageNumberExpected;
            	
		 if(articleType == 'news' || articleType == 'examPage') {
                            checkCount = checkCount - 1 ;
                    }

                 if(( checkCount  > <?php echo $this->config->item('page_count_per_category_page');?>) || (page > <?php echo ( $totalArticles + 10); ?>) ){
				<?php if($currentPage >= 10 || $totalArticles >  ($currentPage/10+1) *100){ ?>
						$('#paging').show();
				<?php } ?>
				$('#content').stopScrollPagination();
				doNotMakeAnotherCall = true;
                 }
             

             }

        }
    });
});     
</script>

<div id="contentArticle" data-enhance="false">
	<?php $this->load->view('mobileArticleSection');?>
</div>

<!-- Loading Div -->
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>

<!-- Display the Pagination links at the end of 10 scrolled pages -->

<!-- Display the Pagination links at the end of 10 scrolled pages -->
<nav id="paging" class="clearfix" style="display:none;">
		
<?php
if($blogType == 'kumkum'){
		$addBlogType = "?type=$blogType";
}

if($currentPage >= 10){	//This is the Next page
	$previousPage = floor($currentPage/10) -1 ;
	$previosPageStart = $previousPage * 100; 
	if($blogType == 'news' || $blogType == 'examPage') {
		if(!empty($categoryId)){
		        if($previosPageStart != 0 ) {
				echo '<a class="prev" href="'.$baseUrl.'-'.$previousPage.'?category='.$categoryId. '"><i class="icon-prev"></i> Previous</a>';
			} else {
				echo '<a class="prev" href="'.$baseUrl.'-'.$previousPage.'"><i class="icon-prev"></i> Previous</a>';
			}
		}else {
			if($previosPageStart != 0 ) {	
				echo '<a class="prev" href="'.$baseUrl.'-'.$previousPage.'"><i class="icon-prev"></i> Previous</a>';
			} else{
				echo '<a class="prev" href="'.$baseUrl.'"><i class="icon-prev"></i> Previous</a>';
			}
		}
	}else {
		if(!empty($categoryId) && $blogType != 'kumkum'){
		        if($previosPageStart != 0 ) {	
					echo '<a class="prev" href="'.$baseUrl.'/'.$previosPageStart.'/10'.'?category='.$categoryId. '"><i class="icon-prev"></i> Previous</a>';	
			}else {	
				      echo '<a class="prev" href="'.$baseUrl.'?category='.$categoryId. '"><i class="icon-prev"></i> Previous</a>';
			}
		}else if(!empty($categoryId) && $blogType == 'kumkum'){
		        if($previosPageStart != 0 ) {	
					echo '<a class="prev" href="'.$baseUrl.'/'.$previosPageStart.'/10'.$addBlogType.'&category='.$categoryId. '"><i class="icon-prev"></i> Previous</a>';	
			}else {	
				      echo '<a class="prev" href="'.$baseUrl.$addBlogType.'&category='.$categoryId. '"><i class="icon-prev"></i> Previous</a>';
			}
				
		}else {
		        if($previosPageStart != 0 ) {
				      echo '<a class="prev" href="'.$baseUrl.'/'.$previosPageStart.'/10'.$addBlogType.'"><i class="icon-prev"></i> Previous</a>';
			}else {	
			          echo '<a class="prev" href="'.$baseUrl.$addBlogType.'"><i class="icon-prev"></i> Previous</a>';
			}
		}
	}
}

if($totalArticles >  ($currentPage/10+1) *100){
	$nextPageNews = (floor($currentPage/10)  + 1 )  * 10  + 1;
	$nextPageStart = (floor($currentPage/10)+1) * 100;
	
	if($blogType == 'news' || $blogType == 'examPage') {
		if(!empty($categoryId)) {
			echo '<a class="next" href="'.$baseUrl.'-'.$nextPageNews.'?category='.$categoryId. '">Next <i class="icon-next"></i></a>';
		}else {
			echo '<a class="next" href="'.$baseUrl.'-'.$nextPageNews.'">Next <i class="icon-next"></i></a>';
		}
	}else {
		if(!empty($categoryId) && $blogType != 'kumkum') {
		              echo '<a class="next" href="'.$baseUrl.'/'.$nextPageStart.'/10'.'?category='.$categoryId. '">Next <i class="icon-next"></i></a>';
		}else if(!empty($categoryId) && $blogType == 'kumkum'){
				echo '<a class="next" href="'.$baseUrl.'/'.$nextPageStart.'/10'.$addBlogType.'&category='.$categoryId. '">Next <i class="icon-next"></i></a>';
		              
		}else {
		              echo '<a class="next" href="'.$baseUrl.'/'.$nextPageStart.'/10'.$addBlogType.'">Next <i class="icon-next"></i></a>';
		}
	}
}
?>
</nav>

<?php if($currentPage == 1 && $totalArticles == 0 ):?>
<nav id="no-result" class="clearfix"><p>No results found</p></nav>
<?php else:?>
<!-- No more results page -->
<?php if($totalArticles < 10 ){ ?>
<nav id="no-result" class="clearfix"><p>No more results</p></nav>
<?php }else{ ?>
<nav id="no-result" class="clearfix" style="display: none;"><p>No more results</p></nav>
<?php } ?>
<?php endif;?>
