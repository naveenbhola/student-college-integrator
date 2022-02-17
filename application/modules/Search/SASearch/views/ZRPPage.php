<?php
if($filterAjaxCall != 1) {
                $isFilterAppliedOnSearchLayer = false;
                if (is_object($searchLayerPrefillData['advancedFilterSelection']) && $searchLayerPrefillData['advancedFilterSelection'] instanceof stdClass){
                    $isFilterAppliedOnSearchLayer = true;
                }else{
                    if ($searchLayerPrefillData['searchType'] == "closedSearch" || $searchLayerPrefillData['searchType'] == "mainBoxClosedSearch") {
                        if (is_array($searchLayerPrefillData['locationNames']) && !empty($searchLayerPrefillData['locationNames'])){
                            $locations = join(", ",array_values($searchLayerPrefillData['locationNames']));
                        }elseif (/*$searchLayerPrefillData['searchType'] == "locationClosedSearch" &&*/ is_array($searchLayerPrefillData['locations']) && !empty($searchLayerPrefillData['locations'])){
                            foreach ($searchLayerPrefillData['locations'] as $key=>$value){
                                $locations .= ", ".$value['locName'];
                            }
                            $locations = substr($locations, 2);
                        }
                    }
                }
?>
                <div class="newSearch">
                    <div class="newSrpWrapper srpZrp">
                        <div class="ulpWrapper">
                            <div class="getSpace">
                                <?php
                                        if ($isFilterAppliedOnSearchLayer){
                                ?>
                                            <h2 class="srpHeading">No results found for selected filters.</h2>
                                            <p class="tryAgain">Click here to reset filters and see results.</p>
                                            <p><a class="dwnLdBtn" onclick="resetSearchLayerFilters()">Search Again</a></p>
                                <?php   }else{
                                ?>          
                                            <?php if($staticSearchUrl == true){ ?>
                                            <h2 class="srpHeading">We did not find any results</h2>
                                            <?php } else { ?>
                                            <h2 class="srpHeading">We did not find any results for <strong>"<?=htmlentities($searchLayerPrefillData['mainSearchBoxText'])?>"</strong>
                                                <?=(($locations)?"in <strong>\"".$locations."\"</strong>":"")?>
                                            </h2>
                                            <?php } ?>
                                            <p class="tryAgain">Click here to begin you search again.</p>
                                            <p><a class="dwnLdBtn openSearchLyr">Search Again</a></p>
                                <?php   }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
<?php   } else{?>
                <div>
                    <div class="newSrpWrapper srpZrp">
                        <div class="ulpWrapper">
                            <div class="getSpace">
                                <h2 class="srpHeading">No results found for selected filters.</h2>
                                <p class="tryAgain">Please clear all filters & try again</p>
                                <p><a class="dwnLdBtn" onclick="SASearchV2ApplyFilterObj.refreshPageContentOnFilterApplication(true);">Clear all filters</a></p>
                            </div>
                        </div>
                    </div>
                </div>
<?php   }?>