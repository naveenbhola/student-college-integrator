<?php
$tempJsArray = array('myShiksha','user');
$headerComponents = array(
                'css'   =>      array('colge_revw_desk'),
                'js' => array('common','ajax-api','ana_common','campusConnectHome'),
                'jsFooter'=>    $tempJsArray,
                'title' =>      $m_meta_title,
                'metaDescription' => $m_meta_description,
                'canonicalURL' =>$canonicalURL,
                'product'       =>'campusConnect',
                'showBottomMargin' => false,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

$this->load->view('common/header', $headerComponents);
?>
<script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>
        $j = jQuery.noConflict();
        var topRankedInstitutes = '';
        var mostViewedInstitutes = '';
        var trendingInstitutes = '';
        var programId = <?php echo $programId; ?>;
</script>
<div class="lh10"></div>    
<div id="content-wrapper-div">
        
  <div class="colgRvwHP2 grybgclr bg-color">
        
    <!--campus-connect-search-container-->
    <?php $this->load->view('campus_connect/searchWidget'); ?>
    
    <div class="rvd_wrapper">
    <!--campus-connect-top-rank-college-container-->
    <?php echo modules::run('CA/CampusConnectController/topRankedAndFeaturedCollegeWidget',$programId,true); ?>
    <?php $this->load->view('campus_connect/topQuestionWidget'); ?>
    
    <!--campus-connect-top-featured-questions-container-->
    <?php //$this->load->view('campus_connect/topQuestionWidget'); ?>
    
    <!--campus-connect-most-tranding-container-->
    <?php echo modules::run('CA/CampusConnectController/mostViewedAndTrendingCollegeWidget',$programId,true); ?>
    
    <!--campus-connect-most-tranding-question-container-->
    <?php $this->load->view('campus_connect/mostViewedQuestionWidget'); ?>
    
    <?php if($programId == 1){ // show these widgets only for MBA/PGDM?>
        <!--campus-connect-static-container-->
        <?php $this->load->view('campus_connect/staticWidget'); ?>
        <!--campus-connect-shortlist-container-->
        <div class="shortlisting-box">
            <?php $this->load->view('examPages/widgets/examPageShortlistWidget'); ?>
        </div>
    <?php }?>

    <div class="rvd_wrapR"></div>
    </div>
  </div>
  <div class="clearFix"></div>
</div>

<?php $this->load->view('common/footer');?>

<script>    
    $j(document).ready(function(){
        setScrollbarForMsNotificationLayer();
        $j('#topQuestionInnerContainer .scrollbar1').tinyscrollbar({'wheelSpeed':250});
        $j('#mostViewedContainer .scrollbar1').tinyscrollbar({'wheelSpeed':250});
        $j('#topQuestionContainer').on('click', '#featuredQuestionList', function(){
                if (!$j(this).hasClass('active')) {
                        trackEventByGA('FEATURED_QUESTIONS_TAB_CCHOME', 'CCHome_Questions_With_Featured_Answers_Tab');
                        $j('#topQuestionInnerContainer').html('<div style="text-align:center;"><img style="margin-top: 150px;" src="/public/mobile5/images/ajax-loader.gif" id="loaderImg"></div>');
                        $j(this).removeClass('active');
                        $j('#topQuestionLink').addClass('active');
                        var param  = "programId="+programId;
                        var url = "/CA/CampusConnectController/getQuestionsWithFeaturedAnswer";
                        $j.ajax({
                                type: "POST",
                                data: param,
                                url: url,
                                success: function(request)
                                {
                                        $j('#topQuestionContainer').html(request);
                                        $j('#topQuestionContainer .scrollbar1').tinyscrollbar({'wheelSpeed':250});
                                }
                        });
                }
        });
        $j('#topQuestionContainer').on('click', '#topQuestionLink', function(){
                if (!$j(this).hasClass('active')) {
                        trackEventByGA('TOP_RANKED_QUESTIONS_TAB_CCHOME', 'CCHome_Questions_In_Top_Ranked_Colleges_Tab');
                        $j('#topQuestionInnerContainer').html('<div style="text-align:center;"><img style="margin-top: 150px;" src="/public/mobile5/images/ajax-loader.gif" id="loaderImg"></div>');
                        $j(this).removeClass('active');
                        $j('#featuredQuestionList').addClass('active');
                        var param  = "instData="+topRankedInstitutes;
                        var url = "/CA/CampusConnectController/getQuestionsForTopRankedIntitutes";
                        $j.ajax({
                                type: "POST",
                                data: param,
                                url: url,
                                success: function(request)
                                {
                                        $j('#topQuestionContainer').html(request);
                                        $j('#topQuestionContainer .scrollbar1').tinyscrollbar({'wheelSpeed':250});
                                }
                        });
                }                
        });
        $j('#mostViewedContainer').on('click', '#mostViewedQuestionLink', function(){
                if (!$j(this).hasClass('active')) {
                        trackEventByGA('MOST_VIEWED_QUESTIONS_TAB_CCHOME', 'CCHome_Questions_Most_Viewed_Questions_Tab');
                        $j('#mostViewedInnerContainer').html('<div style="text-align:center;"><img style="margin-top: 150px;" src="/public/mobile5/images/ajax-loader.gif" id="loaderImg"></div>');
                        $j(this).removeClass('active');
                        $j('#trendingQuestionLink').addClass('active');
                        var param  = {'widgetType':'mostViewed','programId':programId};
                        var url = "/CA/CampusConnectController/mostViewedAndTrendingCollegeQuestions";
                        $j.ajax({
                                type: "POST",
                                data: param,
                                url: url,
                                success: function(request)
                                {
                                        $j('#mostViewedContainer').html(request);
                                        $j('#mostViewedContainer .scrollbar1').tinyscrollbar({'wheelSpeed':250});
                                }
                        });
                }
        });
        $j('#mostViewedContainer').on('click', '#trendingQuestionLink', function(){
                if (!$j(this).hasClass('active')) {
                        trackEventByGA('TRENDING_QUESTIONS_TAB_CCHOME', 'CCHome_Questions_Trending_Questions_Tab');
                        $j('#mostViewedInnerContainer').html('<div style="text-align:center;"><img style="margin-top: 150px;" src="/public/mobile5/images/ajax-loader.gif" id="loaderImg"></div>');
                        $j(this).removeClass('active');
                        $j('#mostViewedQuestionLink').addClass('active');
                        var param  = {'widgetType':'trending','programId':programId};
                        var url = "/CA/CampusConnectController/mostViewedAndTrendingCollegeQuestions";
                        $j.ajax({
                                type: "POST",
                                data: param,
                                url: url,
                                success: function(request)
                                {
                                        $j('#mostViewedContainer').html(request);
                                        $j('#mostViewedContainer .scrollbar1').tinyscrollbar({'wheelSpeed':250});
                                }
                        });
                }                
        });
        $j("#topQuestionContainer, #mostViewedContainer, #topRankedCollegeContainer, #mostViewedCollegeContainer").on('mouseenter', 'h2 a', function() {
                if (!$j(this).hasClass('active')) {
                        $j(this).css('color','#4D4D4D');
                        $j(this).parent().siblings('h2').find('a').css('color','#949494');
                }
        });
        $j("#topQuestionContainer, #mostViewedContainer, #topRankedCollegeContainer, #mostViewedCollegeContainer").on('mouseleave', 'h2 a', function() {
                if (!$j(this).hasClass('active')) {
                        $j(this).css('color','#949494');
                        $j(this).parent().siblings('h2').find('a').css('color','#4D4D4D');
                }
        });
    });
        
        // Code added for College Review Home Page Widget 
        
	var myTimeOut ='';
        $url = $j(location).attr('href');
        
        if($url.indexOf("#mostViewedContainer") > -1) {
                
                myTimeOut = setTimeout(goToByScroll("mostViewedContainer"), 5000);
        } else if($url.indexOf("#topQuestionContainer") > -1) {
              
                myTimeOut = setTimeout(goToByScroll("topQuestionContainer"), 5000);
        }
        
        function goToByScroll(id){
                $j('html,body').animate({
                        scrollTop: $j("#"+id).offset().top-100},'slow');
                clearTimeout(myTimeOut);
        }
        
</script>
