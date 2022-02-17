<?php 
$trackingKeys['trackingPageKeyId']=$trackingPageKeyId;
$trackingKeys['emailtrackingPageKeyId']=$emailtrackingPageKeyId;
$trackingKeys['invitetrackingPageKeyId']=$invitetrackingPageKeyId;
$trackingKeys['loadtrackingPageKeyId']= $loadtrackingPageKeyId;

 header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.

 header('Pragma: no-cache'); // HTTP 1.0.

 header('Expires: 0'); // Proxies.


$tempJsArray = array('myShiksha','user');


$headerComponents = array(
		'css'   =>      array('college-predictorV2'),
		'js' => array('collegePredictor'),
		'jsFooter'=>    $tempJsArray,
		'title' =>      $m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>'collegePredictorV2',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'noIndexNoFollow' => $noIndexNoFollow

);
$headerComponents['showGutterBanner'] = 1;
switch($examName){
	case 'jee-mains': $bannerName = 'JEE';break;
	case 'kcet': $bannerName = 'KCET';break;
	case 'comedk': $bannerName = 'COMEDK';break;
	case 'default': $bannerName = 'JEE';break;
}
$headerComponents['bannerPropertiesGutter'] = array('pageId'=> $bannerName.'_COLLEGE_PREDICTOR', 'pageZone'=>'RIGHT_GUTTER');
$headerComponents['shikshaCriteria'] = array();
$this->load->view('common/header', $headerComponents);
$this->load->view('messageBoard/desktopNew/toastLayer');
?>

<div class="clg-predctr">
    <?php 
        if($defaultView){
           $this->load->view('CP/V2/collegePredictorWelcome');
           $this->load->view('CP/V2/collegePredictorForm');
        }
    ?>
</div>
<?php if(!$defaultView){ ?>
<div class="rslt-contnr pred-container">
               
                <div class="clg-rslt ">
                    <div class="predctr-titl">
                        <?php if($examName =='jee-mains') { ?>
                            <h1><?=$total?> Results Found</h1>
                        <?php } else { ?>
                       <h1><?=$heading?></h1>
                   <?php } ?>
                       <p><?php echo $text;?></p>
                    </div>

                    <div class="mdfy-src">
                        <a href="javascript:void(0);" id="modifySearchButton" href="javascript:void(0);" <?php if(!$defaultView){?> style="display: block;" <?php } else{?> style="display: none;" <?php }?> >Modify Search</a>
                    </div>
                </div>
<?php } ?>
		<?php $examNameNew = strtoupper($examName);
            if(!$defaultView) {
        ?>
                <div class="srcRslt-div">
			<?php if($filterStatus=='YES'){ ?>
                	<div id="filter-all" <?php if($defaultView==1) { ?> style="display:none;" <?php } ?> >
                	<?php $this->load->view('CP/V2/sideFiltersCP'); ?>
			</div>
			<?php } ?>
                    <!--Filter section-->
                    
					<div id="searchResultMainDiv">	
        				<?php $this->load->view('CP/V2/searchResultsCP',$trackingKeys);
                        ?>
        			</div>
    				<div id="loading" style="display:none;">
    			         <img id="loading-image" src="/public/images/gif-load.gif" alt="Loading..." />
    		        </div>
                </div>
		          <div class="clearFix"></div>
            <?php } ?>    
    </div> 
    <div id= 'contentLoaderData' style='display: none;'>
        <?php echo $contentLoaderData; ?>
    </div>
    <?php 
        $this->load->view('common/footer');
    ?>
<script>
<?php if(!$defaultView) { ?>
var startCount = <?php echo $count;?>;
var count = <?php echo $count;?>;
<?php } ?>
</script>
<script>
    <?php if(!$defaultView) { ?>
$j(function() {
		//includeBMSJS();
                 $j(window).scroll(function(){
                     if ($j(this).scrollTop() > 700) {
                           $j('.scrollToTop').fadeIn();
                     } else {
                           $j('.scrollToTop').fadeOut();
                     }
                });
        
                $j('.scrollToTop').click(function(){
                     $j('html, body').animate({scrollTop:0}, 500);
                });
            // document.getElementById('my_tag').onclick = function(){new_func();}
        });

$j(window).load(function(){
 $j('body,html').animate({scrollTop:0},'fast');
});
<?php } ?>
var showFilters = '<?php echo $filterStatus;?>';
var examName = '<?php echo $examinationName;?>';
var GA_currentPage = 'collegePredictor';
var numberOfRound = '<?php echo count($roundData); ?>';
var groupId = '<?php echo $eResponseData['groupId'];?>';

publishBanners();
$j(document).ready(function () {
    initCollegePredictor();
    
    $j(document).on('click','#modifySearchButton',function(){
        console.log(examName)
        if(examName =='JEE-Mains'){
            setCookie('collegepredictor_filterTypeValueData_desktop_'+examName,null,-1,'/',COOKIEDOMAIN);
        }
        setCookie('collegepredictor_search_'+examName,null,-1,'/',COOKIEDOMAIN);
        setCookie('COLLEGE_PREDICTOR_TOP_FILTER_'+examName,null,-1,'/',COOKIEDOMAIN);
        setCookie('collegepredictor_showFilters_'+examName,null,-1,'/',COOKIEDOMAIN);
        window.location.reload();
    });
});


</script>
<?php if(!$defaultView) { ?>
<a href="javascript:void(0);" class="scrollToTop" style="display: none;"></a>
<?php } ?>

<?php
//In case user is not logged in, we have to show the Registration overlay when he has searched
if(isset($_COOKIE["collegepredictor_search_desktop_".$examinationName])) {
        $data = $_COOKIE["collegepredictor_search_desktop_".$examinationName];
        $data = json_decode($data);
        if(isset($data->rank) && !empty($data->rank) && $data->rank > 1){
                echo "<script>showFormInCollegePredictor('engineering');</script>";
        }
}
?>
