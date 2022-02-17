<div class="src-cont">
<?php if($searchTupleType=='course' && $zrpByOpenSearch !== true){?>
<div class="srt-filSec">
<a href="#filterLayer" id="fll" class="first-child" data-transition="slide" data-inline="true"   data-direction="reverse" data-rel="dialog"><i class="search-sprite abFil-icn"></i> Filters </a>
<a href="#sortLayer" id="srtBut" data-transition="slide" data-inline="true" data-rel="dialog"> <i class="search-sprite abSrt-icn"></i> Sort <?php echo $sortParamText;?></a>
</div>
<?php $this->load->view('searchPage/widgets/coachmark'); ?>
<?php } ?>

<?php
if ($searchLayerPrefillData['searchType'] == "closedSearch" || $searchLayerPrefillData['searchType'] == "mainBoxClosedSearch"){
    if (is_array($searchLayerPrefillData['locationNames']) && !empty($searchLayerPrefillData['locationNames'])){
        $locationsValues    = array_values($searchLayerPrefillData['locationNames']);
        if (count($locationsValues) <= 3){
            $locations = join(", ",$locationsValues);
        }else {
            $locations = $locationsValues[0].", ".$locationsValues[1].", ".$locationsValues[2]." + ".(count($locationsValues) - 3)." More";
        }
    }elseif (/*$searchLayerPrefillData['searchType'] == "locationClosedSearch" &&*/ is_array($searchLayerPrefillData['locations']) && !empty($searchLayerPrefillData['locations'])){
        $count = 0;
        foreach ($searchLayerPrefillData['locations'] as $key=>$value){
            if (++$count <= 3){
                $locations .= ", ".$value['locName'];
            }
        }
        $locations = substr($locations, 2).(($count > 3)?", + ".($count - 3)." More":"");
    }
}
if(isset($staticSearchUrl) && $staticSearchUrl == true){
    if(!empty($locationName)){
        $typeText = 'Universities in '.$locationName.' ('.$countryName.') – Courses, Fees & Admissions';        
        echo '<h1 class="rsPge-tit">'.$typeText.'</h1>'; 
    }    
}else{ 
    echo '<div class="rsPge-tit">';
    echo $pageData['totalResultCount']; 
    $typeText = ($pageData['totalResultCount']==1)?'result ':'results ';
    echo $typeText;	
?>
for <strong><?php echo htmlentities($searchedTerm);?></strong> <?=(($locations)?"in <strong>".$locations."</strong>":"")?>
<?php echo '</div>';} ?>

</div>
<div class="srcRs-con" id="resultContainer">
<?php
$dataArray = array(
'identifier' => 'SearchListTupleV2',
'pageType' => 'searchPage_mob'
);
if($pageData['totalResultCount'] == 0){
$this->load->view('widgets/zeroResultPage');
}else{
if($searchTupleType=='university'){
$this->load->view("widgets/searchPageUnivTupleV2",$dataArray); 
}else{
$this->load->view("widgets/searchPageCourseTupleV2",$dataArray); 
}
}
?>
</div>
<?php if($pageData['totalResultCount'] > SA_SEARCH_PAGE_LIMIT) {?>
<div id="course_loadmore_cont" class="loadMoreDiv"><a href="javaScript:void(0);" class="loadmore2">Load More</a></div>
<?php  }?>
<div class="clearFix">&nbsp;</div>
<noscript>
<?php if($relNext){ ?>
<a href="<?php echo $relNext; ?>">Next</a>
<?php } ?>
<?php if($relPrev){ ?>
<a href="<?php echo $relPrev; ?>">Previous</a>
<?php } ?>
</noscript>