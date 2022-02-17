<div class="group-card pop-div">
    <h2 class="head-2 titl"><?php echo htmlentities($heading); ?><a class="cls-head" id="cl-s" onclick="hideAllInstituteLayer();"></a></h2>

    <?php
    if(count($institutesData['institutes']) > 20){ ?>
    <div>
        <div class="course-search">
            <input type="text" name="search" placeholder="<?php echo $searchBoxText;?>" class="ail_coursename">
        </div>
    </div>
    <?php } ?>
    <div class="listing-div scrollbar1">
        <div class="scrollbar" style="height: 335px;">
            <div class="track" style="height: 335px;">
                <div class="thumb" style="top: 0px; height: 133.936px;">
                    <div class="end"></div>
                </div>
            </div>
        </div>
        <div class="viewport">
            <div class="overview" style="top: 0px;">
                <ul class="uni-layer-list flex-ul">
                <?php
                    foreach ($institutesData['institutes'] as $instituteObj) {
                        $instituteName = $instituteObj->getShortName();
                        $instituteName = $instituteName ? $instituteName : $instituteObj->getName();
                        $id=$instituteObj->getId();
                ?>
                        <li class="c-n">
                            <a target="_blank" href="<?php echo $instituteObj->getUrl();?>"><?php echo htmlentities($instituteName);?></a>
                            <div class="location-of-clg">
                            <?php
                            if(!empty($institutesData['aggregateReviewsData'][$id])  && $institutesData['aggregateReviewsData'][$id]['aggregateRating']['averageRating'] > 0) {
                              $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $institutesData['aggregateReviewsData'][$id], 'reviewsCount' => $institutesData['reviewCount'][$id], 'dontHover' => 1));
                            }
                            ?>
                            </div>
                        </li> 

                <?php
                    }
                ?>
                <?php

                ?>
                </ul>
                <div class="col-lg-4 no-data-div hide"><i class="no-data">No results Found !!!</i></div>
            </div>
        </div>
    </div>
</div>
