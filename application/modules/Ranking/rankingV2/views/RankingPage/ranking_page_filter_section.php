<div class="rnk_hdr" id="hdr-cntnt">
    <div class="rnk_hd">
        <h1 class="rnk_tl fnt__sb color-1"><?php echo $meta_details['h1']; ?></h1>
        <span class="block color-1 f-14" >
        <span  id='disclaimerReadMore'>
        <?php if(strlen($rankingPageDisclaimer)>340)
        {
         echo substr($rankingPageDisclaimer,0,340).'... <a href="javascript: void(0);" class="link" id="ReadFullText"> Read More</a></span>';  
        ?>
        <span class='hid' id='disclaimerFullText'> <?php echo $rankingPageDisclaimer;?> </span>
        <?php
        }
        else
        echo $rankingPageDisclaimer;    
        ?>
        </span>
    </div>
    <div class="fltr_sec">
        <div class="fltr_bx f-14 color-1">
            <div class="flt_dv">
            <ul class="fLeft">
                <li><label class="i-block ">Filter List By<span class='filter-tour'>:</span></label></li>
                <li class="exm-bloc3">
                    <label class="col_labl flt-label">Ranked By</label>
                    <div class="flt_slt fnt__sb wd-inht publisherFilter">
                        <p class="ps__rl flt-dv pub_lyr"><span><?=$filters['selectedPublisherFilter']->getName();?> <span class="f-12 fnt__n tp-cnt">(<?=$filters['selectedPublisherFilter']->getCount();?>)</span></span></p>
                        <div class="flt-lyr flt-cstm-lyr fnt__n hid">
                            <ul>
                                <?php foreach ($filters['publisher'] as $publisherFilter) { ?>
                                        <li publisher-name="<?=$publisherFilter->getName();?>" publisher-id="<?=$publisherFilter->getId();?>"><a href="javascript:void(0);" ga-attr="PUBLISHER"><?=$publisherFilter->getName();?> <span class="color-6 f-12">(<?=$publisherFilter->getCount();?>)</span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php if(count($filters['specialization']) > 1) { ?>
                <li class="exm-bloc2">
                    <label class="col_labl">Specialization</label>
                    <div class="flt_slt fnt__sb wd-inht">
                        <p class="flt-dv pub_lyr"><span><?=$filters['selectedSpecializationFilter']->getName();?></span></p>
                        <div class="flt-lyr flt-cstm-lyr fnt__n hid">
                            <ul>
                                <?php foreach ($filters['specialization'] as $specializationFilter) { ?>
                                        <li><a href="<?=$specializationFilter->getUrl();?>" ga-attr="FILTER"><p><?=$specializationFilter->getName(); ?></p></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php } ?>
                <li class="exm-bloc1">
                    <?php 
                        $defaultCity = reset($filters['city']);
                        $defaultState = reset($filters['state']);
                     ?>
                    <label class="col_labl">Location</label>
                    <div class="flt_slt fnt__sb wd-inht">
                        <p class="flt-dv pub_lyr"><span><?=$filters['selectedLocationFilter']->getName();?></span></p>
                        <div class="flt-cstm-lyr ex-lyr fnt__n hid">
                            <div class="ex-bx">
                                <div class="hd-lbl">
                                    <label class="color-1 f-12">States</label>
                                </div>
                                <div class="ex-bxlyr">
                                    <?php foreach ($filters['state'] as $locationFilter) { ?>
                                        <div><a href="<?=$locationFilter->getUrl();?>" ga-attr="FILTER"><?=$locationFilter->getName();?></a></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="ex-bx">
                                <div class="hd-lbl">
                                    <label class="color-1 f-12">Cities</label>
                                </div>
                                <div class="ex-bxlyr">
                                <?php foreach ($filters['city'] as $locationFilter) { ?>
                                    <div><a href="<?=$locationFilter->getUrl();?>" ga-attr="FILTER"><?=$locationFilter->getName();?></a></div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php if(count($filters['exam']) > 1) { ?>
                <li class="exm-bloc">
                    <label class="col_labl">Exam</label>
                    <div class="flt_slt fnt__sb wd-inht">
                        <p class="flt-dv pub_lyr"><span><?=$filters['selectedExamFilter']->getName();?></span></p>
                        <div class="flt-lyr flt-cstm-lyr fnt__n hid">
                            <ul>
                                <?php foreach ($filters['exam'] as $examFilter) { ?>
                                        <li><a href="<?=$examFilter->getUrl();?>" ga-attr="FILTER"><p><?=$examFilter->getName();?></p></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php } ?>
            <?php if($filters['currentPageUrl'] != $filters['resetAllFilter']->getUrl() ) { ?>
                <li>
                    <a href="<?=$filters['resetAllFilter']->getUrl();?>" class="rst-lnk">RESET ALL</a>
                </li>
            <?php } ?>
            </ul>
                <div class="fRight rnk_src" ga-attr="SEARCH">
                    <input type="text" class="rnkSrc_fld color-6 f-14" placeholder="Search a College within this ranking"/>
                    <i class="ico-sprt ico-src"></i>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div class="flt_dv hd_tbl" id="yearHeadingSection">
        <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_year_section'); ?>
    </div>
    </div>
</div>