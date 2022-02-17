<script language = javascript>
var COOKIEDOMAIN = "<?php echo COOKIEDOMAIN; ?>";
</script>

<?php

$locationBuilder = new LocationBuilder;
$locationRepository = $locationBuilder->getLocationRepository();
global $cityList;
$cityList = $locationRepository->getCitiesByMultipleTiers(array(1),2);
$_COOKIE['userCity']=="All Cities";

?>

<?php
/*    if (getTempUserData('confirmation_message')){?>
        <div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
        <?php echo getTempUserData('confirmation_message'); ?>
        </div> 
<?php } 
?>
<?php 
   deleteTempUserData('confirmation_message');*/
?>

                <?php
                        if(empty($h1Title)) {
                            if($request->isMainCategoryPage()){
                                    $pageHeading = $categoryPage->getCategory()->getName();
                                    $change = "Career Option";
                            }elseif($request->isSubcategoryPage()){
                                    $pageHeading =  $categoryPage->getSubCategory()->getName();
                                    $change = "Course";
                                    /*check added to change the text from btech to engineering on mobile category pages for page title
                                      Story Id : LF-2875
                                      Changed By: Aman Varshney
                                    */
                                    if($categoryPage->getSubCategory()->getId() == 55 || $categoryPage->getSubCategory()->getId() == 59) {
                                        $pageHeading =  $categoryPage->getSubCategory()->getName()." colleges";
                                    }
                                    if($categoryPage->getSubCategory()->getId() == ENGINEERING_SUBCAT_ID) 
                                         $pageHeading = 'Engineering';

                            }elseif($request->isLDBCoursePage()){
                                    $change = "Course";
                                    $pageHeading = $categoryPage->getLDBCourse()->getCourseName()." ".($categoryPage->getLDBCourse()->getSpecialization()!="All"?$categoryPage->getLDBCourse()->getSpecialization():"");
                                    if($categoryPage->getSubCategory()->getId() == 55 || $categoryPage->getSubCategory()->getId() == 59) {
                                        if($categoryPage->getSubCategory()->getId() == 59) {
                                            $subcatName = "M.Tech";
                                        } else {
                                            $subcatName = $categoryPage->getSubCategory()->getName();
                                        }
                                        $pageHeading = $subcatName." ".($categoryPage->getLDBCourse()->getSpecialization()!="All"?" in ".$categoryPage->getLDBCourse()->getSpecialization():"");
                                    }
                            }

                            if($categoryPage->getLocality()){
                                    $locationname = $categoryPage->getLocality()->getName();
                            }elseif($categoryPage->getZone()){
                                    $locationname = $categoryPage->getZone()->getName();
                            }elseif($request->getCityId() > 1){
                                    $locationname = $categoryPage->getCity()->getName();
                            }elseif($request->getStateId() > 1){
                                    $locationname = $categoryPage->getState()->getName();
                            }else{
                                    $locationname = $categoryPage->getCity()->getName();
                            }

                            $type = '';
                            if($request->getCategoryId() == 13){
                                $type = 'Courses';
                                if($request->isSubcategoryPage()){
                                    $type = 'Colleges';
                                }
                                if($categoryPage->getTotalNumberOfInstitutes() == 1){
                                    $type = rtrim($type,'s');
                                }
                            }
                        }
                ?>
<div class="head-group" style="padding: 4px 10px 6px; -moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;"  data-enhance="false">
<div class="flLt" style="width:70%">
    <h1 style="display:inline;text-align:left;" title="<?php echo displayTextAsPerMobileResolution($pageHeading,1,true).' in '.$locationname;?>">
    <?php
    $rightStyle = '';
    if(!empty($h1Title)) {
        echo $h1Title; ?>
        <a id="lId" class="change-loc" href="#categoryLocationDiv" data-inline="true" data-rel="dialog" data-transition="slide"><span style="position:relative; top:-1px;display: inline-block; padding: 2px 0px 0px;">Change</span><i class="icon-right" style="top:0px;"></i></a>
        </span>
    <?php }
    else {
        echo displayTextAsPerMobileResolution($pageHeading,1,true);?>
        <br />
    <?php } ?>
    </h1>  
    <?php if(empty($h1Title)) { ?>
        <p style="text-align:left; display:inline-block; margin-top:6px;">in <span class="change-loc transparency"><?php echo substr($locationname,0,11);if(strlen($locationname)>11) {echo '...';}?></p> <a id="lId" class="change-loc" href="#categoryLocationDiv" data-inline="true" data-rel="dialog" data-transition="slide" style="padding:2px 4px 4px; font-size:13px; display:inline-block; font-weight:bold;"><span style="position:relative; top:-1px;display: inline-block; padding: 2px 0px 0px;">Change</span><i class="icon-right" style="top:0px;"></i></a>
    <?php } ?>
</div>

<?php
            if((($categoryPage->getTotalNumberOfInstitutes() > 1)) || (isset($appliedFiltersSet) && count($appliedFiltersSet)>0)){?>
	    
	    <div class="head-filter flRt" id="showFilterButton" style="<?=$rightStyle;?>">
                            <a id="refineOverlayOpen" href="#refineDiv" data-inline="true" data-rel="dialog" data-transition="slide" onClick="trackEventByGAMobile('HTML5_CATEGORY_PAGE_FILTER_ICON');" >
                                    <i class="icon-busy" aria-hidden="true"></i>
                                    <p>Filter</p>
                            </a>
            </div>
            <?php } ?>
<div class="clearfix"></div>
</div>





<!--div class="head-group" data-enhance="false">
            <h1 title="<?php echo displayTextAsPerMobileResolution($pageHeading,1,true).' in '.$locationname;?>">
            	<div class="left-align" style="margin-right: 98px;margin-left: 12px;">
                    <?php echo displayTextAsPerMobileResolution($pageHeading,1,true);?><br />
                    <p>in <span class="change-loc transparency"><?php echo substr($locationname,0,11);if(strlen($locationname)>11) {echo '...';}?>
		    <a id="lId" class="change-loc" href="#categoryLocationDiv" data-inline="true" data-rel="dialog" data-transition="slide">Change<i class="icon-right"></i></a>
		    </span></p>
                </div>
            </h1>
            <?php
            //Only if the Institute count is greater than 1 OR filters are applied, we will show the refine button
            $appliedFilters = $request->getAppliedFilters();
	    $appliedFiltersSet = sanitizeAppliedFilters($appliedFilters,false);
	    ?>
	    
	    <!---------------mylists----------------->
	
	    <?php //$this->load->view('/mcommon5/mobileMyList');?>
	    
	    <!------------end-mylists---------------->
	    
	   <?php
            if((($categoryPage->getTotalNumberOfInstitutes() > 1)) || (isset($appliedFiltersSet) && count($appliedFiltersSet)>0)){?>
	    
	    <!--div class="head-filter" id="showFilterButton">
                            <a id="refineOverlayOpen" href="#refineDiv" data-inline="true" data-rel="dialog" data-transition="slide" onClick="trackEventByGAMobile('HTML5_CATEGORY_PAGE_FILTER_ICON');" >
                                    <i class="icon-busy" aria-hidden="true"></i>
                                    <p>Filter</p>
                            </a>
            </div>
            <?php } ?>
</div-->    
