<?php
	$footerComponents = array(
			    'js'                => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
                'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script>
var compareOverlayTrackingKeyId = 596;
$j(window).scroll(function(){
        var scrollpos = $j(this).scrollTop();
        var windowHeight = $j(this).height();
        var totalHeight = scrollpos+windowHeight;
        var footer = $j('#footer').offset().top;

        var tablescrollTop = $j('#tablescrollTop').outerHeight();

        if(scrollpos <= tablescrollTop || footer < totalHeight ){
                $j('#compHeaderSticky').hide();
        }
        else{
                $j('#compHeaderSticky').show();
        }
});

//to remove courses from compare page
function removeCourseFromComparePage(courseId)
{
    var referrer = document.referrer;
    var isComparePageReferrer =  referrer.indexOf("comparepage");
    var cookieVal = getCookie('compareCourses');
    if(cookieVal!="" && referrer!="")
    {
         removeCourseFromCompare(courseId,0,JSON.parse(cookieVal)); 
    }
     var comparePageUrl = window.location.href;
     var res = comparePageUrl.split("-");
     var index = res.indexOf(courseId);
        if (index > -1) {
            res.splice(index, 1);
        }
     res = res.join("-");
     window.location.href = res; 
     // we need to prevent redirection to a compare page without courseids & send it back to back link
     if(res.indexOf("-")== -1)
     {
        window.location.href = $j(".abroad-compare-clg-title").find('a').attr('href'); 
     }
     else{
        window.location.href = res;
     }
}

function showFullText(obj,str){
     $j('.fullData'+str).show(); 
     $j('.smallData'+str).remove();     
}
</script>
