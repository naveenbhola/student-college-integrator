<?php
    // if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
    							'pages'=>array('commonModule/layers/brochureWithRequestCallback'),
    							'trackingPageKeyIdForReg' => 488
    						);
    $this->load->view('commonModule/footer',$footerComponents);
?>
<?=$trackingId > 0 ? "<script>var trackingId = ".$trackingId.";</script>" :"" ?>
<script>
var searchKeyword = '<?=empty($keywordEncoded) ? "none" : $keywordEncoded?>';	
var pageType = '<?=empty($from_page) ? "searchPage" : $from_page?>';
var courseResultCount = <?=empty($sa_course_count) ? 0 : $sa_course_count?>;
var universityResultCount = <?=empty($university_count) ? 0 : $university_count?>;
var courseResultPageNo = 1;
var universityResultPageNo = 1;
</script>
<script>
$j(document).ready(function($j){
    //replaceConsultantDivs();
    });
</script>