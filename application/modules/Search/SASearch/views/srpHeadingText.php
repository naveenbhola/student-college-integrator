<?php
    if (!$filterAjaxCall) {
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
    }
    $headingText = ($pageData['totalResultCount'])." ".(($pageData['totalResultCount']==1)?'result':'results');
    $headingText .= " found for <strong>".htmlentities($searchedTerm)."</strong>";
    $headingText .= "<locationTag class='headingSearchLocationTerm'>".(($locations)?" in <strong>".$locations."</strong>":"")."</locationTag>";
?>
<div class="getSpace" id="srpHeading">
    <h2 class="srpHeading">
        <?=$headingText?>
    </h2>
    <?php $this->load->view('SASearch/filterSections/sorters')?>
</div>