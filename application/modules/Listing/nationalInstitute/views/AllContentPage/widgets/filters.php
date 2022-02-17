<?php 
    $GA_Tap_On_Filter = 'FILTER';
    $GA_Tap_On_FILTER_Ins = 'INSTITUTE_FILTER';
    $GA_Tap_On_FILTER_Course = 'COURSE_FILTER';
    $GA_Tap_On_Sort = 'SORT';
    $GA_Tap_On_Select_Course = 'SELECT_COURSE_REVIEW';

?>
<?php 
$courseOnClick = '';
if($pageType =='reviews') { 
    $courseOnClick = '$j'."('#selectedFilterRating').val('');".'$j'."('.review-filter-div span.option-slctd').text(".'$j'."('.rating-filter li a:first').text());";
  }
?>
<?php if((!empty($filtersArray['filterInstitutes']) && count($filtersArray['filterInstitutes']) > 0 )|| (!empty($filtersArray['filterCourses']) && count($filtersArray['filterCourses']) > 0 ) || (!empty($sortingOptions) && count($sortingOptions) > 0)) { ?>
<div class="selc-filters clrTpl gen-cat">

    <div class="rslt-div">
    <?php if((!empty($filtersArray['filterInstitutes']) && count($filtersArray['filterInstitutes']) > 0 )|| (!empty($filtersArray['filterCourses']) && count($filtersArray['filterCourses']) > 0 )) { ?>
        <p><span>Filter by : </span> </p>
        <?php if(!empty($filtersArray['filterInstitutes']) && count($filtersArray['filterInstitutes']) > 0 ) {?>
        <div class="dropdown-primary instituteFilter-div">
                <span class="option-slctd" ga-attr="<?=$GA_Tap_On_Filter;?>" ><?php echo !empty($selectedInstituteId)? $filtersArray['filterInstitutes'][$selectedInstituteId] : 'Select college';?></span>
                <span class="icon"></span>
                <ul class="dropdown-nav instituteFilter-ul" id="click-layer2" style="display: none;">
                <div class="instituteTinyBar">
                <div style="">
                    <div list" style="">
                <li class="instituteFilter li-dropdown"><a class="li-dropdown-a dummy-i" href="javascript:void(0);" onclick="$j('#selectedInstituteId').val('');resetFilters(); <?php echo $courseOnClick;?> updateAllContentByAjax();" ga-attr="<?=$GA_Tap_On_FILTER_Ins;?>">Select college</a></li>
                <!-- <li class="instituteFilter" onclick="resetFilters('<?php echo $instituteKey;?>')"><a id="<?php echo $instituteKey;?>">Select Institut</a></li> -->
                <?php foreach($filtersArray['filterInstitutes'] as $instituteKey => $instituteValue) {  
                    ?>
                    <li class="instituteFilter li-dropdown"><a class="li-dropdown-a" id="<?php echo $instituteKey;?>" href="javascript:void(0);" onclick="$j('#selectedInstituteId').val(<?php echo $instituteKey;?>);fireGTM('institute','<?php echo $instituteKey;?>');resetFilters(<?php echo $instituteKey;?>); <?php echo $courseOnClick;?>updateAllContentByAjax();" ga-attr="<?=$GA_Tap_On_FILTER_Ins;?>"><?php echo htmlentities($instituteValue);?></a></li>
                    <?php } ?>
                    </div>
                    </div>
                    </div>
                </ul>

        </div>
        <?php } 
        if(!empty($filtersArray['filterCourses']) && count($filtersArray['filterCourses']) > 0 ) {
        ?>
        <div class="dropdown-primary courseFilter-div">
                <span class="option-slctd courseOption" ga-attr="<?=$GA_Tap_On_Filter;?>" ><?php echo !empty($selectedCourseId)? $filtersArray['filterCourses'][$selectedCourseId] : 'Select course';?></span>
                <span class="icon"></span>
                <ul class="dropdown-nav courseFilter-ul" id="click-layer2" style="display: none;">
            <div class="courseTinyBar">
                <div style="">
                    <div style="">
                    <li class="courseFilter li-dropdown course_dummy"><a class="li-dropdown-a dummy-c" href="javascript:void(0);" onclick="$j('#selectedCourseId').val('');$j('#selectedTagId').val('');<?php echo $courseOnClick;?> updateAllContentByAjax();updateDropDownText(this);" ga-attr="<?=$GA_Tap_On_FILTER_Course;?>">Select course</a></li>
                   <!--  <li class="" id="default"><a id="">Select Course</a></li> -->
                    <?php foreach($filtersArray['filterCourses'] as $courseKey => $courseValue) {  ?>


                    <li class="courseFilter li-dropdown course_<?php echo $courseKey;?>">
                     
                        <a class="li-dropdown-a" id="<?php echo $instituteKey;?>" href="javascript:void(0);" onclick="createResponseOnClick(this,'<?=$courseKey?>');$j('#selectedTagId').val('');" ga-attr="<?=$GA_Tap_On_Select_Course;?>" customCallBack="callBackRegistrationSelectCourse" courseId = '<?php echo $courseKey;?>' cta-type="read_course_review" customActionType = 'Read_Course_Review'><?php echo htmlentities($courseValue);?></a>
                              
                    </li>
                    <?php } ?>
                    </div>
                </div>
                </div>
                </ul>
        </div>
        <?php } }?>
    </div>
    <?php $this->load->view("nationalInstitute/AllContentPage/widgets/reviewRatingFilter");?>

    <?php if(!empty($sortingOptions) && count($sortingOptions) > 0 && $pageType!='reviews')  {?>
    <div class="sticky-rt">
            <p><span>Sort by</span> </p>
            <div class="dropdown-primary no-mrgn sortByOption">
                <span class="option-slctd" ga-attr="<?=$GA_Tap_On_Sort;?>"><?php echo ucfirst($selectedSortOption);?></span>
                <span class="icon"></span>
                <ul class="dropdown-nav" id="click-layer2" style="display: none;">
                    <?php foreach($sortingOptions as $sortKey => $sortValue ){ ?>
                        <li><a href="javascript:void(0);" ga-attr="<?php echo strtoupper(str_replace(' ','',$sortValue))."_".$GA_Tap_On_Sort;?>" onclick="updateAllContentBySort(this);"><?php echo $sortValue;?></a></li>
                    <?php }
                    ?>
                </ul>
        </div>
    </div>
    <?php } ?>
</div>
<?php } ?>