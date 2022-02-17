<?php
    
    //truncate insitute name after 110 chars
    $instituteShortString = (strlen($institute->getName()) > 100) ? substr($institute->getName(), 0, 100).'..' : $institute->getName();
    //get header image
    $displayLocation = $displayDataObject[$course->getId()]->getDisplayLocation();
    if(!empty($displayLocation)){
        $instituteThumbURL = $institute->getHeaderImage($displayLocation->getLocationId());    
    }
    
    $locationSuffixForUrl = $displayDataObject[$course->getId()]->getLocationSuffixForUrl();
    if(!empty($instituteThumbURL)){
        $instituteThumbURL =getImageVariant($instituteThumbURL->getURL(),3);
    }
    // _P($instituteThumbURL);die;
    $displayLocationString = $displayDataObject[$course->getId()]->getDisplayLocationString();
    
    $imageAvailable=true;
    if(!($instituteThumbURL)) {
        $imageAvailable=false;;
        $instituteThumbURL = '/public/images/cat_default_desktop.png';
    }

    
    $paidClass = "";
    if($course->isPaid()){
        $paidClass = " tpl-paid";
    }
    $totalMediaCount =0;

    if($displayLocation)
    {
    $totalMediaCount = getMediaCountForInstitute($institute,$displayLocation->getLocationId());
    }

?>
 <?php 
        if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && in_array($product,array('AllCoursesPage','Category','SearchV2')) && !empty($trackingSearchId)){
            global $TupleClickParams;
            $trackingstring  = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}=$tuplenumber&{$TupleClickParams['listingtypeid']}={$institute->getId()}";
            $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
            if(!empty($trackingFilterId)){
                $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
            }
        
            $trackingStringImage = $trackingstring."&{$TupleClickParams['clicktype']}=instituteImg";
            $trackingStringGallery = $trackingstring."&{$TupleClickParams['clicktype']}=instituteGallery";
            $trackingStringName = $trackingstring."&{$TupleClickParams['clicktype']}=institute";
        }

    ?>


<section class="tuple-clg-name<?php echo $paidClass;?>">    
    <a  class="<?php if($imageAvailable) echo "tuple-clg-img"; ?>" href="<?php echo add_query_params($institute->getURL().$locationSuffixForUrl,$trackingStringImage);?>" target="_blank"><img  data-original="<?php echo $instituteThumbURL; ?>" src="//<?php echo IMGURL; ?>/public/images/cat_default_desktop.png" alt='<?php echo html_escape($institute->getName()); ?>' class="tuple-clg-img lazy"/></a>
    <noscript>
    <a  class="<?php if($imageAvailable) echo "tuple-clg-img"; ?>" href="<?php echo add_query_params($institute->getURL().$locationSuffixForUrl,$trackingStringImage);?>" target="_blank"><img src="<?php echo $instituteThumbURL; ?>" class="tuple-clg-img"></a>
    </noscript>

    <?php if($totalMediaCount>0 && $imageAvailable && in_array($product,array('AllCoursesPage','Category','SearchV2')) ) { ?> 
    <a class="selfi__img"  href="<?php echo add_query_params($institute->getURL().$locationSuffixForUrl,$trackingStringGallery);?>#gallery" target="_blank"><?php   echo $totalMediaCount; ?></a>
    <?php } ?>

    <h2 class="tuple-clg-heading"><a class="tuple-institute-name" href="<?php echo add_query_params($institute->getURL().$locationSuffixForUrl,$trackingStringName);?>" target="_blank"><?php echo htmlentities($instituteShortString)?>
    <?php 
        if(in_array($product,array('AllCoursesPage','Category','SearchV2'))){
            ?>
            <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Institute'] ?></p></span>
            <?php
        } ?>
        </a>
    
        <p>| <?php echo $displayLocationString;?></p>
        <?php if($isInstituteMultilocation[$institute->getId()]) { ?>
            <a href="<?php echo $isInstituteMultilocation[$institute->getId()]; ?>" target="_blank" class="mr-brnLnk">+more branches</a>
        <?php } ?>
    </h2>
    <?php if(DEBUGGER) { if(!empty($debugSortInfo['instituteDebugSortParam'][$institute->getId()])) _p("Popularity Score: ".$debugSortInfo['instituteDebugSortParam'][$institute->getId()]); }?>
    <?php 
      $admissionURL = $institute->getURL().'/admission';
      $isAdmissionPage = $institute->isAdmissionDetailsAvailable();
      $pageType = $product;
      if($product =='ebochureCallback'){
        $pageType = 'RecommendationLayer';
      }
      if($product =='SearchV2'){
        $pageType = 'Search';
      }
      if($isAdmissionPage){
    ?>
      <a href='<?php echo $admissionURL;?>' class="adms-det" ga-page="<?php echo $pageType;?>" ga-attr="PC_Tuple_Admission" ga-optlabel="Admission_Link" target='_blank'>View Admission Details</a>  
    <?php } ?>

    <?php
    global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
    $instituteFacilitiesListSorted = $displayDataObject[$course->getId()]->getFacilities();
     if(!empty($instituteFacilitiesListSorted)) {
        if($isAdmissionPage)
        { ?>
         <span class="adm-spn"> | </span>

    <?php } ?>
        <ul class="facility-icons <?php if(!$isAdmissionPage){ echo "width100";}?>" >
            <?php $countOfFacilityData=0; foreach ($instituteFacilitiesListSorted as $facilityData) {
                $facilityId = $facilityData[0];
                $facilityName = $facilityData[1];
                if(!empty($FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityId])) {
                    if($countOfFacilityData ==11 && $isAdmissionPage){
                        break;
                    }    
                    $class1 = 'fc_icons ic_fac_'.$FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityId];
                    $facilityDesc = "";
                    if(empty($facilityDesc)) {
                        $class2 = 'emptyDesc';
                    } else {
                        $class2 = '';
                    }?>
                    <li class="<?php echo $class2; ?>">
                        <i class="<?php echo $class1; ?>">
                            <div class="srpHoverCntnt2">
                                <h3><?php echo $facilityName ?></h3>
                                <p><?php echo $facilityDesc ?></p>
                            </div>
                        </i>
                    </li>
                <?php
                $countOfFacilityData++; } 
            } ?>
        </ul>
    <?php } ?>
</section>
