
<?php
    $totalCourses = count($courseObject);
    $result_limit = 1;
    $urlFetch = SHIKSHA_HOME."/mobile_myShortlist5/MyShortlistMobile/myShortlistInstituteSearch?start=2&exam=$examName&cutoff=$cutOff&location=$location";
    //endif;as
    $currentPage = 1;
    $urlArray = explode('start=',$urlFetch);
    $paginationURLPrev = $urlArray[0];
    $urlArray = explode('&',$urlArray[1]);
    for($i=1;$i<(count($urlArray));$i++){
        $paginationURLNext .= ($paginationURLNext=='')?$urlArray[$i]:'&'.$urlArray[$i];
    }
?>
<script>
var doNotMakeAnotherCall = false;
<?php if($totalCourses < 5) { ?>
    var doNotMakeAnotherCall = true;
    <?php } ?>
var pageNumberExpected = 3;
var checkForSize= true; 
var noResultFound = false;


$(function(){
     $.fn.fadeInWithDelay = function(){
         var delay = 0;
         return this.each(function(){
             $(this).delay(delay).animate({opacity:1}, 200);
             delay += 100;
         });
     };
    
     $('.shortlistCourseResult').scrollPagination({
         'contentPage': '<?=$urlFetch?>',
         'contentData': ({type: 'fetchAjax'}),
         'scrollTarget': $(window),
         'heightOffset': 100,
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
         }
     });
});           
</script>


                <div class="mys-search">
                    <p style="margin:0 0 5px 1px">Search results (<?=$totalCourses?>):</p>   
                    <div class="mys-subnav">
                        <div class="mys-lft" style="margin-left:1px;">
                            <a class="mys-btn-white" style="margin-right:5px;" id="selectedExamNameAndScore"><?=$examCutOffSelected?></a>
                            <a class="mys-btn-white" style="margin-right:5px;" id="selectedExamLocation"><?=$locationText?></a>
                        </div>
                        <div class="mys-rgt">
                            <a data-rel="back" class="mys-btn-white-blue">Modify</a>
                        </div>
                        <p class="clr"></p>
                    </div>      
                 </div>

                 <?php if($totalCourses == 0){ ?>
                 <div class="shortlistNoCourseResult">
                 <nav id="no-result" class="clearfix" style="display: block;"><p>Results not found</p></nav>
                 </div>
                 <?php } else {?>
                <div class="shortlistCourseResult" id="shortlistCourseResult">
                <?php
                if( isset( $tracking_keyid ) ) {
                    $this->load->view('mobile_myShortlist5/myShortlistSearchByExamResultSnippets', array('tracking_keyid'=>$tracking_keyid));
                } else {
                    $this->load->view('mobile_myShortlist5/myShortlistSearchByExamResultSnippets');
                }
                ?>
                </div>
                        <?php } ?>

 <!-- Loading Div -->
<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" src="/public/mobile5/images/ajax-loader.gif" border=0 alt="" ></div>         
<!-- No more results page -->
<nav id="no-result" class="clearfix" style="display: none;"><p>No more results</p></nav>
<script type="text/javascript">
//If the Number is less than 10, we will stop pagination
if(doNotMakeAnotherCall && noResultFound){
    $('#no-result').show();
    $('#content').stopScrollPagination();
    doNotMakeAnotherCall = true;
    noResultFound = true;
    checkForSize = false;
}

</script>