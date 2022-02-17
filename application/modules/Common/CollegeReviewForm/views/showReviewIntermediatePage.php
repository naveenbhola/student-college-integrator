<?php
foreach ($currentTileData as $tileData){
	$tileId = $tileData[0]['tileId'];
	if($pageNo<=1){
		$title = $tileData[0]['title'];
		$pageTitle = $tileData[0]['seoPageTitle'];
		$desc = $tileData[0]['seoPageDescription'];
		$canonical = $tileData[0]['seoUrl'];

        if($totalPages > 1){
            $nextURL = $tileData[0]['seoUrl'].'/'.($pageNo+1);
        }
	}
	else{
                $title = $tileData[0]['title'];
                $pageTitle = "Page $pageNo - ".$tileData[0]['seoPageTitle'];
                $desc = "Page $pageNo - ".$tileData[0]['seoPageDescription'];
                $canonical = $tileData[0]['seoUrl']."/$pageNo";
                if($pageNo < $totalPages){
                        $nextURL = $tileData[0]['seoUrl'].'/'.($pageNo+1);
                }
                
                        $previousURL = $tileData[0]['seoUrl'].'/'.($pageNo-1);
	}
}
$headerComponents = array(
                'css'   =>      array('colge_revw_desk'),
                'js' => array('common'),
                'jsFooter'=>    array('collegeReviewHomepage'),
                'title' =>      $pageTitle,
                'metaDescription' => $desc,
                'canonicalURL' => $canonical,
                'product'       =>'collegeReviewHomepage',
                'showBottomMargin' => false,
                'previousURL' => $previousURL,
                'nextURL' => $nextURL,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

  $this->load->view('common/header', $headerComponents);
?>

<?php $this->load->view( 'messageBoard/headerPanelForAnA',array('collegePredictor' => true) );?>

   <div id="content-wrapper-div">

     <div class="clearFix"></div>

     <div class="colgRvwHP2 grybgclr">
    
    
    <div class="rvd_wrapper">

        <div id="searchOpacityLayer"></div>
    <div id="searchReviewLayer" style="display: none;position:absolute;left: 29%;z-index: 10000;"></div>

         <!-- Left Side Widget STart -->
         <div class="rvd_wrapL">
        <a class="backLnk" href="javascript:void(0)" onClick="window.history.go(-1)"><< Back</a>
    <div class="src-box">
        
        <div class="rv_srchfeld">
          <div class="cr_inputsrchBx bordRdus3px" style=" box-shadow:none;" id="mainWrap">
            <div class="cr_inputsrchBxInnr" id="searchBox"> <i class="icns-colgrvw icn-search-small" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME;?>','keywordSuggest');" style="cursor: pointer;"></i>
              <input type="text" placeholder="Search Reviews by College Name" name="keyword" id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('','focus');" autocomplete="off"/>
            </div>
            
            <div class="cr_sugestd openCollegeReviewSuggester" id="tileSuggested" style="display: none;">
            <ul id="suggestions_container" style="text-align: left; display: block; "></ul>
            <ul id="suggestedList" ></ul>
           </div> 
          </div>
          
          <p class="clr"></p>
        </div>
    </div>  
<div style=" clear: both; height: 25px;"></div>

        <h2 style="color: #000000;margin-bottom: 10px;font-size: 20px;text-align: left;display: inline-block;font-weight: normal;line-height: 31px;"><?=$pageTitle?></h2>
        <?php
        if(is_array($currentTileData['bottom'][0]))
        {
            $currentTile = $currentTileData['bottom'][0];
        }
        else{
            $currentTile = $currentTileData['top'][0];
        }
        if($currentTile['description'] != '' && strlen($currentTile['description']) > 165)
        {
        ?>
        <p id="currentTileDescription1" style="padding: 0 2px; color: #878787; font-size: 14px; margin-bottom: 14px;"><?=substr($currentTile['description'],0,165)?>...&nbsp;<a href="javascript:void(0);">more</a></p>
        <p id="currentTileDescription2" style="display: none; padding: 0 2px; color: #878787; font-size: 14px; margin-bottom: 14px;"><?=$currentTile['description']?></p>
        <?php
        }
        else if($currentTile['description'] != '')
        {
        ?>
        <p id="currentTileDescription1" style="padding: 0 2px; color: #878787; font-size: 14px; margin-bottom: 14px;"><?=$currentTile['description']?></p>
        <?php
        }
        ?>
        <?php $this->load->view('reviewsIntermediateWidget',$currentTile); ?>
        <?php $this->load->view('disclaimer'); ?>
        <?php $this->load->view('reviewCollegeWidget'); ?>
        
         </div>
         <!-- Left Side Widget Ends -->
         
         
         <!-- Right Side Widget STart -->
         <div class="rvd_wrapR" style="width:293px !important;">
        <h2 style="color: #000000;margin-bottom: 10px;font-size: 20px;text-align: left;display: inline-block;font-weight: normal;line-height: 31px;">Review Collections</h2>
        <?php $this->load->view('tilesWidget',array('currentTileId'=>$tileId)); ?>
        <?php $this->load->view('registrationWidget',array('pageNameRegister'=>'CollegeReviewIntermediatePage')); ?>
        <?php global $managementStreamMR;
            if($stream == $managementStreamMR) $this->load->view('naukriToolWidget'); ?>

         </div>
         <!-- Right Side Widget Ends -->
         
         
         <p class="clr"></p>
       </div>
       
     </div>
     <div class="clearFix"></div>
     
   </div>

<?php
  $this->load->view('common/footer');
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<div id="toTop">&#9650; Back to Top</div>
<?php
$this->load->view('autoSuggestorCollegeReviews');
?>
<script>
var basePageUrl = '<?=$basePageUrl?>';

var $stream = ""+"<?=$stream;?>";
var $baseCourse = ""+"<?=$baseCourse;?>";
var $substream = ""+"<?=$substream;?>";
var $educationType = ""+"<?=$educationType;?>";
var $pageRead = 'collegeReviewIntermediate';

</script>
