<?php
    if($collegesWidgetData['topInstituteData'] || $collegesWidgetData['providesAffiliaion']){
?>
<div class="new-row listingTuple" id="colleges-offer">
                    <div class="group-card gap academic-card">
                        <?php
                            if($collegesWidgetData['topInstituteData']){
                        ?>
                        <div class="head-1 gap">
                            <h2 class="ulp">Colleges / Departments</h2>
                            <?php if(!empty($collegesWidgetData['providesAffiliaion'])) { ?>
                                <div class="tp-block">
                                  <i class="info-icn"></i>
                                  <div class="tooltip top">
                                      <div class="tooltip-arrow"></div>
                                      <div class="tooltip-inner">
                                          <?php echo $instituteToolTipData['affiliated_definition']['helptext'];?>
                                      </div>
                                  </div>
                                </div>
                            <?php } ?>
                        </div>
                        <p>
                            <strong class="crse-title"><?=$collegesWidgetData['constituentCollegeText'];?> <?php echo htmlentities($instituteName);?></strong>
                        </p>
                        <?php if(empty($collegesWidgetData['providesAffiliaion'])) {
                            $removingMargin = 'rm-border';
                            }?>
                        <div class="gap <?=$removingMargin;?>">
                                <ul class="flex-ul col-3 acd-unt-sec">
                                <?php
                                    $intituteNameLimit = 90;
                                    //_p($collegesWidgetData);
                                    //die();
                                    foreach ($collegesWidgetData['topInstituteData'] as $id => $instituteObj) {

                                        $instituteFullName = $instituteObj->getShortName();
                                        $instituteFullName = $instituteFullName ? $instituteFullName : $instituteObj->getName();
                                        $instituteTimmedName = strlen($instituteFullName) > $intituteNameLimit ?  (substr($instituteFullName, 0, $intituteNameLimit)."...") : $instituteFullName;
                                ?>
                                        <li ga-attr="COLLEGE_ACAMEMIC">
                                            <p>
                                                <a href="<?php echo $instituteObj->getUrl();?>" title="<?php echo htmlentities($instituteFullName);?>" target="_blank"><?php echo htmlentities($instituteTimmedName);?></a>
                                            </p>
                                             <?php
                                            if(!empty($collegesWidgetData['aggregateReviewsData'][$id])  && $collegesWidgetData['aggregateReviewsData'][$id]['aggregateRating']['averageRating'] > 0 && $collegesWidgetData['reviewCount'][$id] > 0 ){
                                                ?>
                                            <div class="location-of-clg">
                                           <?php
                                              $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $collegesWidgetData['aggregateReviewsData'][$id], 'reviewsCount' => $collegesWidgetData['reviewCount'][$id]));
                                            
                                            ?>
                                            </div>
                                            <?php } ?>
                                        </li>
                                <?php } ?>
                                </ul>
                                <?php if($collegesWidgetData['showViewMoreCTA']){ ?>
                                <div class="alter-div align-center">
                                    <button class="button button--secondary arw_link" ga-attr="VIEW_ALL_COLLEGE_ACADEMIC" onclick="showAllInstituteLayer(<?php echo $listing_id;?>);">View all</button>
                                </div>
                                <?php } ?>
                        </div>
                        <?php
                        }
                        if($collegesWidgetData['providesAffiliaion']){ ?>
                        <div class="c" id="affiliatedSec">
                        <div class='fntot-dv'>
                            <span class="para-3 w-txt"><?=$collegesWidgetData['affiliatedCollegesText']?></span>
                            <button ga-attr="VIEW_AFFILIATED_COLLEGES" class="button button--secondary arw_link" onclick="openAffiliationLayer(<?php echo $listing_id;?>);">Click Here</button>
                        </div>
                        </div>
                        <?php } ?>

                    </div>
</div>
<?php
    }
?>
