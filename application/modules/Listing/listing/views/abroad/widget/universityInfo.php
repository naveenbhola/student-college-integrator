<div class="university-details clearwidth">
      <i class="listing-sprite box-pointer"></i>
            <?php $university = $universityObj;
            $department = $departmentObj;?>
            <?php if($university->getLogoLink()){ ?>
                <a href="<?=$university->getURL()?>"><img src="<?=$university->getLogoLink()?>" width="290" height="90" alt="col-logo" /></a>
            <?php } ?>
      <?php if($listingType == 'university'){ ?>
      <div class="university-name">
            <h2 class="font-14"><a href="<?=$university->getURL()?>"><?=htmlentities($university->getName())?></a></h2>
            <?php $location = '';
            if(reset($university->getLocations())->getCity()->getName())
            {
                  $location = reset($university->getLocations())->getCity()->getName();
            }
            if(reset($university->getLocations())->getState()->getName())
            {
                  $location .= ", ".reset($university->getLocations())->getState()->getName();
            }
            ?>
            <p><?=$location?><span class="pipe">|</span> <?php if(reset($university->getLocations())->getCountry()->getName()){echo reset($university->getLocations())->getCountry()->getName();}?></p>
      </div>
      <div class="details" style="word-wrap: break-word;">
            <?php $university_type = '';
                  if($university->getTypeOfInstitute()=='not_for_profit')
                  {
                        $university_type .= "Not For Profit"." ".ucfirst($university->getTypeOfInstitute2());
                  }else{
                        $university_type .= ucfirst($university->getTypeOfInstitute())." ".ucfirst($university->getTypeOfInstitute2());
                  }
                  if($university->getEstablishedYear())
                  {
                        $university_type .= ", Estd in ".$university->getEstablishedYear();
                  }
                  if($university_type != ''){
            ?>
            <p><?=$university_type?></p>
            <?php }?>
            <p><?php if($university->getAffiliation()){echo "Affilation: ".$university->getAffiliation();}?></p>
            <p><?php if($university->getAccreditation()){echo "Accreditation: ".$university->getAccreditation();}?></p>
        <?php if($university->getInternationalStudentsPageLink()){?>
            <p><a href="<?php
            if(0===strpos($university->getInternationalStudentsPageLink(),'http')){
                  echo $university->getInternationalStudentsPageLink();
            }else{
                  echo "http://".$university->getInternationalStudentsPageLink();
            }
                  ?>" target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'outgoingLink');">International Student Website<i class="common-sprite ex-link-icon"></i></a></p>
            <?php }
            
        /*
         * Showing 2nd Breadcrumb for Course Listing page..
         */   
        if($listingType == 'course' || $listingType == 'snapshotcourse') {  ?>
            <div style="border-top:1px solid #EAEAEA;margin: 5px 0;" class='clearwidth'></div>
            <div id="otherBreadcrumb" style="float:none;margin:auto;width:auto;"><?php
            foreach($otherBreadcrumbData as $key => $breadCrumb) {	
            ?>
                 <span itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><?php
                            if($breadCrumb['url'] != "") {
                            ?>
                              <a href="<?=$breadCrumb['url']?>" itemprop="url"><span itemprop="title"><?=htmlentities($breadCrumb['title'])?></span></a>
                              <span class="breadcrumb-arr">&rsaquo;</span>
                              <?php
                            } else {  ?>
                               <span itemprop="title"><?=htmlentities($breadCrumb['title'])?></span>
                            <?php } ?>		
                 </span>
            <?php
            }
            ?>
            </div>
        <?php
        }
        ?>
      </div>
      <?php } else { ?>
      <div class="university-name">
            <h2 class="font-14"><a href="<?=$university->getURL()?>"><?=$university->getName()?></a></h2>
            <?php $location = '';
            if(reset($university->getLocations())->getCity()->getName())
            {
                  $location = reset($university->getLocations())->getCity()->getName();
            }
            if(reset($university->getLocations())->getState()->getName())
            {
                  $location .= ", ".reset($university->getLocations())->getState()->getName();
            }
            ?>
            <p><?=$location?><span class="pipe">|</span> <?php if(reset($university->getLocations())->getCountry()->getName()){echo reset($university->getLocations())->getCountry()->getName();}?></p>
          
            <a href="<?=$university->getURL()?>" class="univ-detail-button">View university details <span class="flRt univ-arrow">&gt;</span></a>
      </div>
      <?php } ?>
</div>
