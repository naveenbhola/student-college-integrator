<?php if((isset($isMyShortlistHomePage) && $isMyShortlistHomePage == true) || (isset($isCourseDetailsTabsPage) && $isCourseDetailsTabsPage == true)){ ?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>"></script>
<script type="text/javascript">
  if(getCookie("isMyShortlistWalkthroughShown") == "") {
    setCookie("isMyShortlistWalkthroughShown","1","30");
    setTimeout(function(){$('.help').click()},1000);
  }
</script>
<?php } ?>

<script>
<?php if(isset($isMyShortlistHomePage) && $isMyShortlistHomePage == true){ ?>

     $(document).ready(function(){
    initializeAutoSuggestorInstanceForMyShortlist();
    showRecommendationWidgetShortlist();
});

var myShortlistSuggestorInstance = null;
var mobileSearch = 'true';
var tempInstituteField = ''; 

<?php } ?>

 <?php if(isset($isCourseDetailsTabsPage) && $isCourseDetailsTabsPage == true){ ?>

     $(document).ready(function(){
    showRecommendationWidgetShortlist();
    });


<?php } ?>


<?php if(isset($isCourseSnapshotPage) && $isCourseSnapshotPage == true) {?>

    $(".right-menu").bind('click',function(event) {
        $('#courseSnapshotShortlistStickyBtn').css('visibility', 'hidden');
    });

    var inititalScrollVal = $(window).scrollTop();  
    var nonStickyDivTop   = $('#courseSnapshotShortlistBtn').offset().top;
    $(document).on('scroll', function() {
        var scrollTopVal = $(window).scrollTop();
        var nonStickyDivTop = $('#courseSnapshotShortlistBtn').offset().top;
        var stickyDivTop = $('#courseSnapshotShortlistStickyBtn').offset().top;
        var footerTop = $('#page-footer').offset().top;

        if((!hamburgerFlag && typeof(hamburgerFlag) !='undefined') && (!rightHamburgerFlag && typeof(rightHamburgerFlag) !='undefined')) {
            if(stickyDivTop < nonStickyDivTop) {
                $('#courseSnapshotShortlistStickyBtn').css('visibility', 'visible');
            }else {
                $('#courseSnapshotShortlistStickyBtn').css('visibility', 'hidden');
            }
            inititalScrollVal = scrollTopVal;
        }
        else {
            $('#courseSnapshotShortlistStickyBtn').css('visibility', 'hidden');
        }
    });

    $('#courseSnapshotShortlistStickyBtn').attr("onclick","shortlistBtnCourseSnapshot('btn')");
    $('#courseSnapshotShortlistBtn').attr("onclick","shortlistBtnCourseSnapshot('btn')");
    $('.mys-shortlst-btn').attr("onclick","shortlistBtnCourseSnapshot('star')");

<?php }?>




<?php if(isset($isCourseSearchPage) && $isCourseSearchPage == true) {?>
    $('body').on('click','.listing-tupple',function(event){
        if($(event.target).hasClass('shortlist-star') || $(event.target).hasClass('shortlisted-star')){
            showMyShortlistLoader();
            courseId = $(this).find(".courseInfoToPass courseId").html();
            var searchListingUrl = window.location.href;
			var shortlistAction = 'add'; //check if already shortlisted, send action 'delete' if so
			if($('#'+'shortlistedStar'+courseId).hasClass('shortlisted-star')) {
				shortlistAction = 'remove';
			}
            var trackingKeyId = <?php echo isset ($tracking_keyid ) ? $tracking_keyid : 0 ; ?>;
			gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist', 'search_page_'+shortlistAction);
            showRegistrationForm(courseId, 'shortlist', 'NM_MyShortlist', searchListingUrl,'shortlistSearchExamCallback',this, trackingKeyId);
        }else{
            showMyShortlistLoader();
            populateCourseDetailSnapshot(this);         
        }
    }); 

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
     //console.log("URL"+opts.contentPage);
     $.ajax({
          type: 'POST',
          url: opts.contentPage,
          data: opts.contentData,
          success: function(data){     
            if(data == 'noresults' || data.length<5){    //Show No result message and stop auto pagination                   
                $('#no-result').show();
                $('#content').stopScrollPagination();
                doNotMakeAnotherCall = true;
                noResultFound = true;
                checkForSize = false;
            }
            else{
                $('#shortlistCourseResult').append(data);
                var resultCount = $('.listing-tupple').length;
                $('.mys-search > p').html("Search results ("+resultCount+")");
            }
            var objectsRendered = $("#shortlistCourseResult").children('[rel!=loaded]');         
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
     'afterLoad': null  ,
     'scrollTarget': null,
     'heightOffset': 0        
};  
})( jQuery );
<?php } ?>     


<?php if((isset($isMyShortlistHomePage) && $isMyShortlistHomePage == true) || (isset($isCourseDetailsTabsPage) && $isCourseDetailsTabsPage == true)){ ?>

var courseId = '';
var instituteId = '';
$("[course-option]").on("click", function (e) {
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
    $('.mys-overlay').show();
    showMyShortlistLoader();
    var tracking_keyid = 0;
    courseId      = parseInt($(this).siblings(".courseInfoToPass").find("courseId").html());
    instituteId   = parseInt($(this).siblings(".courseInfoToPass").find("instituteId").html());
    instituteName = $(this).siblings(".courseInfoToPass").find("instituteName").html();
    tracking_keyid = parseInt($(this).siblings(".courseInfoToPass").find("tracking_keyid_DEB").html());
    //courseId
    var recData = {'instituteId':instituteId,'courseId':courseId,'instituteName':instituteName,'shiksha_site_current_url':'<?=$shiksha_site_current_url;?>','shiksha_site_current_refferal':'<?=$shiksha_site_current_refferal;?>','tracking_keyid':tracking_keyid};
    $('.myshortlistActionPoints').html('');
    $.ajax({
         url:'/mobile_myShortlist5/MyShortlistMobile/showSettingLayer',
         type: "POST",
         data: recData,
         success: function(result){
             if(result != "No Data Found") {
                 $('#popupBasicBack').hide();
                 $('.myshortlistActionPoints').html(result);
                 toShowActionPointsLayer();
                 $('header#page-header').css('z-index','98');

                 $('body').on('click','#request_e_brochure'+courseId,function() {
                    showMyShortlistLoader();
                 });
                 
                 // report Incorrect Button Action
                 $('#report_incorrect_box'+courseId).attr('onclick','showReportIncorrectLayer('+courseId+')');

                 // apply online
                $("#startApp"+courseId).click(function(event) {
                      toHideActionPointsLayer();
                      $('.mys-overlay').hide();
                      $('.mys-report').hide('slow');
                });

                 // delete Shortlist Button Action
                 var pageType = '';
                 <?php if($isCourseDetailsTabsPage){?>
                  pageType="courseTab";
                  <?php } else { ?>
                  pageType="home";
                  <?php } ?>
                 
                 $('#deleteShortlist'+courseId).attr("onclick","deleteShortlistedCollege("+courseId+",'"+pageType+"')");
            }
         },
     });
    
  });



  $('body #mys-overlay').on('click',function() {
    toHideActionPointsLayer();
    $('header#page-header').css('z-index','');
    $('.mys-overlay').hide();
    $('.mys-report').hide('slow');
     enableWindowScroll();
   });

<?php } ?>     
</script>
<script type="text/javascript">
//open course layer for shortlist
var courseListLayerData = {'subCatMap':'', 'categoryMap':'', 'layerPageId':'newHomepageToolLayer', 'tabSelected':'', 'hierarchyMap':''};
var homepageWidgets = new homepageWidgetsClass(courseListLayerData);
$(document).ready(function(){
    homepageWidgets.homepageDOMReadyCalls(homepageWidgets);
    homepageWidgets.homepageOnLoadCalls(homepageWidgets);
});
</script>
<?php if(isset($isCourseDetailsTabsPage) && $isCourseDetailsTabsPage == true){ ?>
<script src="//<?php echo JSURL; ?>/public/js/BeatPicker.min.js"></script>
<?php } ?>