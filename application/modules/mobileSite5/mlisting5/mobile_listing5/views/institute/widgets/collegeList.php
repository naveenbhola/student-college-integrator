<?php 
    $toolTipData = $this->config->item("instituteToolTipData");
    if($collegesWidgetData['topInstituteData'] || $collegesWidgetData['providesAffiliaion']){
?>
<div class="university-recat listingTuple" id="colleges-offer">
    <div class="university-recat crs-widget">
        <?php
            if($collegesWidgetData['topInstituteData']){
        ?>
        <h2 class="head-L2 margin-left">Colleges / Departments
            <?php if($collegesWidgetData['providesAffiliaion']){?>
                <a><i class="clg-sprite clg-inf" ga-attr="HELP_TEXT" id="affiliated_help_text"></i>
            <?php } ?>
            </a>
        </h2>
        <?php if($collegesWidgetData['providesAffiliaion']){?>
        <div id="affiliated_help_text_layer" class="hid">
        <div class="glry-div amen-div">
            <div class="hlp-info">
                <div class="loc-list-col">
                    <div class="prm-lst">
                        <div class="amen-box">
                            <p class="para-L3"> <?=$toolTipData['affiliated_definition']['helptext']?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php } ?>
        <div class="lcard">
            <h3 class="clg-dpt"><?=$collegesWidgetData['constituentCollegeText'];?> <?php echo htmlentities($instituteName);?></h3>
            <ul class="vwd-clg-lst">
                 <?php
                        $intituteNameLimit = 90;
                        foreach ($collegesWidgetData['topInstituteData'] as $id => $instituteObj) {
                            $instituteFullName = $instituteObj->getShortName();
                            $instituteFullName = $instituteFullName ? $instituteFullName : $instituteObj->getName();
                            $instituteTimmedName = strlen($instituteFullName) > $intituteNameLimit ?  (substr($instituteFullName, 0, $intituteNameLimit)."...") : $instituteFullName;
                ?>
                    <li ga-attr="COLLEGE_ACAMEMIC">
                        <p>
                            <a href="<?php echo $instituteObj->getUrl();?>" title="<?php echo htmlentities($instituteFullName);?>"><?php echo htmlentities($instituteTimmedName);?>
                            </a>
                        </p>
                        <?php
                            if(!empty($collegesWidgetData['aggregateReviewsData'][$id])  && $collegesWidgetData['aggregateReviewsData'][$id]['aggregateRating']['averageRating'] > 0 && $collegesWidgetData['reviewCount'][$id] > 0) {
                        ?>
                        <div class="new_rating">
                            <?php
                            $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $collegesWidgetData['aggregateReviewsData'][$id], 'reviewsCount' => $collegesWidgetData['reviewCount'][$id]));
                            ?>
                        </div>
                        <?php } ?>
                    </li>
                    
                <?php } ?>
            </ul>
            <?php if($collegesWidgetData['showViewMoreCTA']){ ?>
                <a class="btn-mob-blue" ga-attr="VIEW_ALL_COLLEGE_ACADEMIC" onclick="showAllInstituteLayer(<?php echo $listing_id;?>,'colleges','Colleges / Dept.')">View All</a>
                
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    
    <?php if($collegesWidgetData['providesAffiliaion']){?>
    <div class="gap">
        <div class="lcard end-col">
            <h2 class="head-L2"><?=$collegesWidgetData['affiliatedCollegesText']?></h2>
            <a href="javascript:void(0);" class="btn-mob-blue" onclick="showAllInstituteLayer(<?php echo $listing_id;?>,'affiliation','Affiliated Colleges')" ga-attr="VIEW_AFFILIATED_COLLEGES">click Here</a>
        </div>
    </div>
    <?php } ?>
    <div style="display:none;">
        <input id="allInstituteLayer_input"/>
    </div>
    <div class="select-Class" style="display:none;">
        <select style="display:none;" id="allInstituteLayer" sticky-search-heading="Colleges / Dept." show-search="1" onchange="redirectAllInstitute(this)"></select>
    </div>
</div>

<?php } ?>
<?php if(!empty($collegesWidgetData['instituteCount'])){?>
<script>
var totalColleges = '<?php echo $collegesWidgetData['instituteCount'];?>';
</script>
<?php } ?>


