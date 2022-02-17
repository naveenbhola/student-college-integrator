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
		'css'   =>      array('college-predictor'),
		'js' => array('common','ajax-api','collegePredictor'),
		'jsFooter'=>    $tempJsArray,
		'title' =>      $m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>'collegePredictor',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

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

if(!$print){
	$this->load->view('common/header', $headerComponents);
}

?>
<?php if($print):?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("college-predictor"); ?>" type="text/css" rel="stylesheet" />
<?php endif;?>

<script
	type="text/javascript"
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>

<div id="top-nav" style="visibility:hidden;height:0px"></div>
<?php //$this->load->view('messageBoard/headerPanelForAnA',array('collegePredictor' => true));?>


            <div id="predictor-wrap">
                <?php 
                    if($defaultView){
                       $this->load->view('CP/collegePredictorWelcome');
                    }
                ?>
            	<div class="predictor-head">
            			<?php if(!$print):?>
				<h1 class="<?php if($defaultView){echo "hid";} ?>"><?=$heading;?></h1>
			<h4 id="result_text_1">
                        <?php echo $text2;?>
                        </h4>
</div>
<div class="modifyBtn">
						<a id="modifySearchButton" href="javascript:void(0);"
						<?php if(!$defaultView){?> style="display: block;" <?php } else{?> style="display: none;" <?php }?>
						onclick="toggleTool();"
						<?php if(!$defaultView){?> class="orange-button2 flRt"  <?php } else{?> class="orange-button2-disable flRt"  <?php }?>>
						<i class="predictor-sprite modify-icon"></i>Modify Search</a>
				<?php else:?>
			            	<div class="flLt">
			            	<img border="0" class="flLt" title="Shiksha.com" alt="Shiksha.com" src="<?php echo SHIKSHA_HOME;?>/public/images/shiksha-logo.png">
			            	</div>
            			<?php endif;?>
                </div> 
                <?php if($print):?>
                <div style="margin:15px 0; font-size:15px; float:left; width:100%">Dear Shiksha user, given below is your JEE mains prediction for year 2014.Best of luck!! Shiksha Team</div>
                <?php endif;?>
                <div class="clearFix"></div>           
                <div id="predictor-box">
                <span style="font:bold 24px Tahoma,Geneva,sans-serif; color: #898989; padding: 10px 5px; background: #fff; display: none;" id="sticky_text"><?=$heading;?></span>
                    <?php if(!$print):?>
                    <?php $this->load->view('CP/sideTabsCP');?>
                
                	<?php $this->load->view('CP/searchBoxCP'); ?>
                	<?php endif;?>
		</div>
		<?php $examNameNew = strtoupper($examName);?>
                <div class="result-container">
                	<?php if(!$print):?>
                	<h4 id="result_text">
                        <?php echo $text1;?>
                	</h4>
			<?php //if( $_COOKIE['collegepredictor_showFilters']=='notdisplay'){?>
                	<p style="color:#0065DE;margin:-10px 0 20px 0;display:none;" id="zero_result"><a href="javascript:void(0);" onclick="toggleTool();">Please modify your search by clicking here</a></p>
			<?php //}
			?>
			<?php if($filterStatus=='YES'){ ?>
                	<div  id="filter-all" <?php if($defaultView==1 && !$print || $_COOKIE['collegepredictor_showFilters_'.$examinationName]=='notdisplay'){ ?> style="display:none;" <?php } ?> >
                	<?php $this->load->view('CP/sideFiltersCP'); ?>
			</div>
			<?php } ?>    
                	<?php endif;?>
					<div id="searchResultMainDiv">	
        				<?php $this->load->view('CP/searchResultsCP',$trackingKeys);?>
        			</div>
					<div id="loading" style="display:none;">
			 <img id="loading-image" src="/public/images/gif-load.gif" alt="Loading..." />
		       </div>
					
                </div>
		<div class="clearFix"></div>
            </div>
<?php if(!$print){
	$this->load->view('common/footer');
}?>
<?php if($print){
?>
<script>
$j('#course-nav-main').hide();
$j('#cateSearchBlock').hide();
$j('#cateSearchBlock').parent().hide();
$j('body').css('background', '#fff');
</script>
<?php
}
?>
<script>
<?php if($defaultView):?>
window.onload = setCookie('showBox','yes',0 ,'/',COOKIEDOMAIN);
<?php endif;?>
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<?php if(!$defaultView){?>
<script>
$j('#predictor-box').hide();
</script>
<?php } ?>
<?php if($callType!='Ajax' && !$defaultView){ ?>
<script>
$j('.scrollbar').css("visibility", 'visible');
$j('.scrollbar1').tinyscrollbar();
if($('branchContainer') !=null){
  applyScrollBarForFilterByCategory('branch');
}
if($('locationContainer') != null){
  applyScrollBarForFilterByCategory('location');
}
if($('collegeContainer') != null){
  applyScrollBarForFilterByCategory('college');
}
</script>
<?php }
?>
<script>
$j(function() {
		//includeBMSJS();
                $j(window).scroll(function() {
					   if(($j(this).scrollTop() >= $j('#predictor-wrap').offset().top+($j(document).height()/4))
                           && !(/MSIE ((5\\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32")) {
                                if($j(window).width() < 1000){
                                        $j('#toTop').css('left',($j('#predictor-wrap').offset().left+818) + "px");
                                }else{
                                        $j('#toTop').css('left',($j('#predictor-wrap').offset().left+925) + "px");
                                }
                                $j('#toTop').fadeIn();
                        } else {
                                $j('#toTop').fadeOut();
                        }
                });
         
                $j('#toTop').click(function() {
                        $j('body,html').animate({scrollTop:0},500);
                });     
        });
var showFilters = '<?php echo $filterStatus;?>';
var examName = '<?php echo $examinationName;?>';
publishBanners();
$j(document).ready(function () {
    collegePredictorCourseCompare = new collegePredictorCourseCompareClass();
    collegePredictorCourseCompare.refreshCompareCollegediv();

    myCompareObj.setRemoveAllCallBack('collegePredictorCourseCompare.compareCallbackOnCourseRemove');
});

</script>
<div id="toTop">&#9650; Back to Top</div>

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
