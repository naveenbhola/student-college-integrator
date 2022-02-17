<?php
$currentSourceId=current($rankingPageSource)['source_id'];
if($previousRankFlag){
    $previousSourceId=next($rankingPageSource)['source_id'];
    $previousRank=$rankingPage->getPublisherData();
    $previousYear=$previousRank[$previousSourceId]['year'];
}
?>
<section class="zero__rslt" style="display: none;">
  <p class="f20__semi clr__6">Sorry no colleges found for &quot;<span class="searchterm"></span>&quot;</p>
  <article class="top__10">
    <p class="f14__semi clr__6">Here is what you can do :</p>
    <ul class="zrp__ul">
      <li class="clr__6">Check your spelling.</li>
      <li class="clr__6">Try more general words.</li>
    </ul>
  </article>
</section>
<?php 
if(empty($rankingPage)){
 echo "<tr><td colspan='5'><p><i style='color:#FD8103;'>Sorry, no results were found matching your selection. Please alter your refinement options above.</i></p></td></tr>";
}
$chunkedRankingpageData = array_chunk($rankingPage->getRankingPageData(),$tuplesPerPage,true);
  $flag=0;
foreach ($chunkedRankingpageData as $pageNumber => $rankingPageData) {
  $count = 1;
  $hasIIMPredictorWidget = false;
  $hasToolWidget = false;
  $hasEngineeringBanner = false;
  $hasCategoryWidget = false;
  foreach($rankingPageData as $courseId => $pageData){
    $this->load->view('mranking5/ranking_page/ranking_page_tuple',array('pageData'=>$pageData,'currentSourceId'=>$currentSourceId,'previousSourceId'=>$previousSourceId,'previousYear' => $previousYear,'tupleNumber' => $count, 'pageNumber' => $pageNumber));
    if ($count==3&&$flag==0) {
        $flag=1;
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'RP_Slot1'));
    }
    if ($count==7&&$flag==1) {
      $flag++;
      $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'RP_Slot2'));
    }


    if($count == 10 && $rankingPageOf['FullTimeMba'] == 1){ 
      $hasIIMPredictorWidget = true;
      ?>
      <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> ranking__touple clear__float">
        <?php echo $IIMPredictorWidget; ?>
      </section>
      <?php
    }
    if($count == 20 &&  $rankingPageOf['FullTimeMba' ] == 1){ 
      $hasToolWidget = true;
      ?>
      <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> clear__float">
        <?php 
        $bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MOBILE');
        $this->load->view('mranking5/ranking_page/ranking_tool_widget',$bannerProperties1); 
        ?>
      </section>
      <?php 
    }
    if(0 && $count == 10 && $rankingPageOf['Engineering'] == 1){ 
      $hasEngineeringBanner = true;
      ?>
      <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> ranking__touple clear__float engbanner">
      <?php
         if($pageNumber == 0){ 
        $bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MOBILE');
        $this->load->view('common/banner',$bannerProperties1);
        }
         ?>     
      </section>
      <?php 
    }
    if($count == 15){
      $hasCategoryWidget = true;
     ?> 
    <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> cat_widget">
    <?php 
    if($pageNumber == 0){
      $this->load->view('mranking5/ranking_page/ranking_category_widget'); 
    } ?>
    </section>  
    <?php
    }
    $count++;
  }
  if ($count<3&&$flag==0) {
    $flag=30;
    $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'RP_Slot1'));
  }
  if($hasIIMPredictorWidget == false && $rankingPageOf['FullTimeMba'] == 1){ ?>
    <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> ranking__touple clear__float">
      <?php echo $IIMPredictorWidget; ?>
    </section>
    <?php
  }
  if(0 && $hasEngineeringBanner == false && $rankingPageOf['Engineering'] == 1){ 
    ?>
    <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> ranking__touple clear__float engbanner">
      <?php 
       if($pageNumber == 0){ 
      $bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MOBILE');
      $this->load->view('common/banner',$bannerProperties1); 
      }
      ?>
    </section>
    <?php
  }  
  if($hasCategoryWidget == false)
  { ?>
    <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> cat_widget">
    <?php 
    if($pageNumber == 0){
      $this->load->view('mranking5/ranking_page/ranking_category_widget'); 
    } ?>
    </section>
    <?php
  }
  if($hasToolWidget==false && $rankingPageOf['FullTimeMba']==1){ 
    ?>    
    <section pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>  clear__float">
      <?php  $this->load->view('mranking5/ranking_page/ranking_tool_widget'); ?>
    </section>
    <?php
  }
}
?>
<?php
$data['totalPages'] = count($chunkedRankingpageData);
$data['maxPageOnPaginationDisplay'] = 4;
if($data['totalPages']>1) 
  $this->load->view('mranking5/ranking_page/ranking_page_pagination',$data); 
?>
<section class='ranking__touple shw-tblDv'><a href="javascript:void(0)" class="shwDat-link" id="rnkTbl-btn">Show Data in Table</a>
<div class="rnkgTbl-shdwLyr"></div>
</section>
<script>
    // isCompareEnable=true;
    <?php if($tupleType == 'institute') { ?>
        var rankingCriteriaCourses = JSON.parse(JSON.stringify(<?php echo json_encode($rankingCriteriaCourses); ?>));
    <?php } ?>
</script>

