<?php foreach($tupleData as $scholarshipId=>$tuple) { ?>
<div class="flt__lft__100">
    <div class="card__dflt flt__lft__100">
        <a class="div__block fnt__14__bold clr__3a" href="<?php echo $tuple['url']; ?>"><?php echo htmlentities($tuple['name']); ?></a>
        <ul class="list__li flt__lft__100">
            <li class="clear__both">
                <div class="fnt__12 clr__6">Applicable for</div>
                <div class="fnt__12__bold clr__3"> <?php echo htmlentities($tuple['applicableForStr']); ?> <?php echo ($tuple['category']=='external' && $tuple['applicableForStr2'] != ''?'<a data-rel="dialog" data-transition="slide" href="#nMoreLayerContainer" class="nMore ctry">'.$tuple['applicableForStr2'].'</a>':''); ?></div>
                <input type="hidden" class="moreList" value="<?php echo base64_encode(json_encode($tuple['allApplicableCountries']));?>">
            </li>
            <li class="clear__both">
                <div class="fnt__12 clr__6">Course stream</div>
                <div class="fnt__12__bold clr__3"><?php echo $tuple['streamStr']; ?> <?php echo ($tuple['streamStr2'] != ''?'<a data-rel="dialog" data-transition="slide" href="#nMoreLayerContainer" class="nMore strm">'.$tuple['streamStr2'].'</a>':''); ?></div>
                <input type="hidden" class="moreList" value="<?php echo base64_encode(json_encode($tuple['allStreams']));?>">
            </li>
            <li class="clear__both">
                <div class="fnt__12 clr__6">Scholarship type</div>
                <div class="fnt__12__bold clr__3"><?php echo $tuple['type']; ?></div>
            </li>
            <li class="clear__both">
                <div class="fnt__12 clr__6">Amount per student</div>
                <div class="fnt__12__bold clr__3"><?php echo $tuple['amountStr1'].' '.($tuple['amountStr2'] != ''?$tuple['amountStr2']:''); ?></div>
            </li>
            <li class="clear__both">
                <div class="fnt__12 clr__6">No. of awards</div>
                <div class="fnt__12__bold clr__3"><?php echo $tuple['awards']; ?></div>
            </li>
            <li class="clear__both">
                <div class="fnt__12 clr__6">Final deadline</div>
                <div class="fnt__12__bold clr__3"><?php echo $tuple['finalDeadline']; ?></div>
            </li>
            <?php if($tuple['restriction'] != ''){ ?>
            <li class="clear__both">
                <div class="fnt__12 clr__6">Special restriction</div>
                <div class="fnt__12__bold clr__3"><?php echo $tuple['restriction']; ?> <?php echo ($tuple['restriction2'] != ''?'<a data-rel="dialog" data-transition="slide" href="#nMoreLayerContainer" class="nMore restrict">'.$tuple['restriction2'].'</a>':''); ?></div>
                <input type="hidden" class="moreList" value="<?php echo base64_encode(json_encode($tuple['allRestrictions']));?>">
            </li>
            <?php } ?>
        </ul>
        <?php
        if($tuple['brochureAvailable'] == true){
        ?>
        <div class="clear__both">
            <a href="javascript:void(0);" id="db<?php echo $scholarshipId?>" data-rel="dialog" data-transition="slide" class="schlrs-db crse-btn2" schrId="<?php echo $scholarshipId ?>" tkey="1339" uAction="schr_db" srcpg="SCP_MOBILE_DOWNLOAD_BROCHURE_TUPLE">Email Brochure</a>
            <a class="crse-btn" href="<?php echo $tuple['url']?>">View & Apply</a>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php } ?>
<?php if($somecondition){ $this->load->view('scholarshipPage/scholarshipCategoryPage/widgets/scholarshipBanner'); } ?>
<?php if($totalTupleCount > ($request->getPageNumber()*$request->getSnippetsPerPage())){ ?>
<div class="mb__20 flt__lft__100">
    <div class="load-more mt__20">
        <a id="loadMoreTuples" href = "Javascript:void(0);" data-link="<?php echo $request->getPaginatedUrl(($request->getPageNumber())+1,true); ?>" >Load More</a>
    </div>
</div>
<?php }else if(count($tupleData) == 0){ ?>

<div class="noResult-message">
    <p>No Search Results were found for selected filters.Please clear all filters & Try Again</p>
    <a class="new_abrd_btn">Clear all filters</a>
</div>
<?php } ?>