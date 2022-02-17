<?php 
$searchTabOrder = array('Colleges', 'Exams', 'Questions', 'Careers');
//_p($product); die;
if(in_array($product, array('anaDesktopV2')) || in_array($pageType, array('questions')) ) {
    $searchTabOrder = array('Questions', 'Colleges', 'Exams', 'Careers');
}
else if(in_array($product, array('eventCalendar', 'examPage', 'allExams'))) {
    $searchTabOrder = array('Exams', 'Colleges', 'Questions', 'Careers');
}
else if(in_array($product, array('CareerProduct'))) {
    $searchTabOrder = array('Careers', 'Colleges', 'Exams', 'Questions');
}

//for search header across shiksha
if($searchWithHeader) { ?>
    <div class="inside-gnbpage"><a href = '<?php echo(SHIKSHA_HOME . '/searchLayer'); ?>'>
            <div class="pwadesktop-srchbox">
                Search Colleges, Courses, Exams, Questions and Articles
                <button type="button" name="button" class="srchBtnv1">Search</button>
            </div></a>
    </div>
<?php }
//for search header on home page
else {
  $coverBannerUrl = 'background: #A8A8A8 url(data:image/gif;base64,R0lGODlhAwADAIAAAKioqIiIiCH5BAAAAAAALAAAAAADAAMAAAIDDI5XADs=) repeat;';
  if($_REQUEST['loadFromStatic']) {
    $coverBannerUrl = reset(reset($homepageCoverBannerData))['image_url'];
    if(!empty($coverBannerUrl)) {
        $coverBannerUrl = 'background-image: url('.$coverBannerUrl.')';
    }
  }
   //round off logic
    function roundToTheNearest($value, $roundTo){
        return floor($value / $roundTo) * $roundTo;
    }  
 ?>
<div id="search-background-search-page" class="search-background-search-page-transition" style="<?=$coverBannerUrl?>">
        <div class="pwadesktop-search">
            <div class="placeon-center">
                <div class="search-mainBox">
                    <div class="search-heading">
                        <h1 class="pageHeading">Find Colleges, Courses & Exams that are Best for You</h1>
                    </div>
                    <div class="search-items">
                        <ul class="search-itemsList">
                            <li>
                                <strong><?php echo roundToTheNearest($hpCounterResult['national']['instCount'], 1000);?>+</strong>
                                <span>Colleges</span>
                            </li>
                            <li>
                                <strong><?php echo roundToTheNearest($hpCounterResult['national']['shikshaCourses'], 5000);?>+</strong>
                                <span>Courses</span>
                            </li>
                            <li>
                                <strong><?php echo roundToTheNearest($hpCounterResult['national']['reviewsCount'], 5000);?>+</strong>
                                <span>Reviews</span>
                            </li>
                            <li>
                                <strong><?php echo roundToTheNearest($hpCounterResult['national']['examCount'], 50);?>+</strong>
                                <span>Exams</span>
                            </li>
                        </ul>
                    </div>
                    <a href = '<?php echo(SHIKSHA_HOME . '/searchLayer'); ?>'>
                        <div class="pwadesktop-srchbox">
                            Search Colleges, Courses, Exams, Questions and Articles
                            <button type="button" name="button" class="srchBtnv1">Search</button>
                        </div>
                    </a>
                </div>                
            </div>
        </div>
  <a href="#" rel="nofollow" target="_blank" class="cover-banner-link"></a>
  <div class="homePageBannerOvrly"></div>
  
</div>
<?php 
}
?>
<?php
if(!isset($homepageCoverBannerData)) {
  $homepageCoverBannerData = array();
}
$homepageCoverBannerData = escapeArrForJsonEncoding($homepageCoverBannerData);
?>
<?php if($product == 'home') { ?>
<script type="text/javascript">
  var homepageCoverBannerData = eval('(' + '<?=json_encode($homepageCoverBannerData);?>' + ')');
</script>
<?php } ?>
