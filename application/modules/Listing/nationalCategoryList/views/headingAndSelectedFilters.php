<div class="container pL0 pLR0">
    <?php if($product != "AllCoursesPage") { ?>
        <span class="count-col">
            <?php  if($tupleListSource == "categoryPage") {
                if($totalInstituteCount == 1) {
                    $heading = str_replace('colleges', 'college', $heading);
                    $heading = str_replace('courses', 'course', $heading);
                    $heading = str_replace('institutes', 'institute', $heading);
                }
                echo $totalInstituteCount;
            } ?>
        </span>
        <h1 class="searchHead">
            <?php  if($tupleListSource == "categoryPage") {
                echo $heading;
            }else if($tupleListSource == "searchPage") {
                if($relevantResults){
                    echo 'Sorry, we could not find any results ';
                }
                else{
                    echo "<span>{$totalInstituteCount}</span>";
                    echo ($totalInstituteCount == 1)? ' college':' colleges';
                } ?>
                for <?php echo '&#8220;'.htmlentities($keyword).'&#8221;';?><?php
            } ?>
        </h1>
        <?php  
        if($tupleListSource == "categoryPage") {
            ?>
            <span class="offrd-courses"> Offering <?php echo $totalCourseCountForAllInstitutes; ?> Courses</span>
            <?php
        }
        ?>
    <?php } ?>

    <?php
    if($relevantResults) {
        $headingStr = 'You may also be interested in the following colleges:';
        if($relevantResults == 'spellcheck') {
            $headingStr = 'Showing results for &#8220;'.htmlentities($searchKeyword).'&#8221;';
        }else{
            if(!empty($strikeMessage))
                $headingStr = 'Showing results for &#8220;'.$strikeMessage.'&#8221;';
            else if($relevantResults == "relax"){
                $headingStr = "You may also be interested in the following colleges:";
            }
            else{
                $headingStr = 'Showing results for &#8220;'.htmlentities($searchKeyword).'&#8221;';
            }
        } ?>
        <div class="breadcrumb2-sugestn">
            <p class="sugstn-p1">Suggestion: Please enter only a college/course name or check spellings</p>
            <p class="sugstn-p2"><?php echo $headingStr; ?></p>
        </div>
    <?php } 

    if($product == 'Category' && !empty($h2Text)) {
        if(!$isLocationCTP) { ?>
            <p class="course-Hd-Det">Check the <strong>list of all <?php echo $h2Text['criteria']; ?> colleges/institutes in <?php echo $h2Text['locationString']; ?> listed on Shiksha</strong>. Get all information related to admissions, fees, courses, placements, reviews & more on <?php echo $h2Text['criteria']; ?> colleges in <?php echo $h2Text['locationString']; ?> to help you decide which college you should be targeting for <?php echo $h2Text['criteria']; ?> admissions in <?php echo $h2Text['locationString']; ?>.</p>
        <?php } else { ?>
            <p class="course-Hd-Det">Check the <strong>list of all colleges/institutes in <?php echo $h2Text['locationString']; ?> listed on Shiksha</strong>. Get all information related to admissions, fees, courses, placements, reviews & more on colleges in <?php echo $h2Text['locationString']; ?> to help you decide which college you should be targeting for admissions in <?php echo $h2Text['locationString']; ?>.</p>
        <?php } ?>
    <?php } ?>

	<p class="clr"></p>
    <?php if($product == "AllCoursesPage") { ?>
        <div class="all-courses-head"> 
            <p class="total-rslts">Showing <strong style="padding-left:2px;font-weight:600;color:#404041"> <?php echo $totalCourseCount;?> course<?php if($totalCourseCount > 1)echo "s";?> </strong></p>
            <h1 class="searchHead"><?=$seoData['pageHeading'];?></h1>
            <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" class="db-btn brochureForInst" style="position:absolute;right:20px;top:22px;" onclick='ajaxDownloadEBrochure(this,<?php echo $instituteObj->getId();?>,"institute","","<?php echo $tupleListSource;?>",<?php echo $stickyEbrochureTrackingId; ?>)'>
                <span class="tup-view-details">Apply Now</span>
            </a>
            <?php if(!empty($seoData['seoScriptedText'])) { ?>
                <h2 class="course-Hd-Det"><?=$seoData['seoScriptedText']?></h2>
            <?php } ?>
        </div>
  
        <?php if($product == "AllCoursesPage") {if(!empty($selectedFilters)) { ?>
            <div class="slctd-fltrtags slctd-fltrtags-withback">
              <label>Selected Filters :</label>
                <span>
                <?php foreach($selectedFilters as $filterName => $filterValues) {
                    switch($filterName) {
                        case 'stream':
                            break;

                        case 'et_dm':
                        case 'sub_spec':
                            foreach($filterValues as $id => $name) { ?>
                                <a href="javascript:void(0);" data-section="<?=$filterName;?>" data-val="<?=$filterName."*".$id?>"><?php echo $name;?><i>x</i></a>
                            <?php }
                            break;

                        default:
                            foreach($filterValues as $id => $name) { ?>
                                <a href="javascript:void(0);" data-section="<?=$filterName;?>" data-val="<?=$fieldAlias[$filterName]."_".$id?>"><?php echo $name;?><i>x</i></a>
                            <?php }
                            break;
                        }
                    } ?>
                </span>
              <a class="clearAll">Clear All</a>
            </div>
        <?php }} ?>
  
    <?php } ?>
     
    <?php if(!empty($selectedFilters) && $product != "AllCoursesPage") { 

        ?>
        <div class="slctd-fltrtags " style="padding-top:20px">
            <label>Selected Filters :</label>
            <span>
                <?php foreach($selectedFilters as $filterName => $filterValues) {
                    switch($filterName) {
                        case 'stream':
                            break;

                        case 'et_dm':
                        case 'sub_spec':
                            foreach($filterValues as $id => $name) { ?>
                                <a href="javascript:void(0);" data-section="<?=$filterName;?>" data-val="<?=$filterName."*".$id?>"><?php echo $name;?><i>x</i></a>
                            <?php }
                            break;

                        default:
                            foreach($filterValues as $id => $name) { ?>
                                <a href="javascript:void(0);" data-section="<?=$filterName;?>" data-val="<?=$fieldAlias[$filterName]."_".$id?>"><?php echo $name;?><i>x</i></a>
                            <?php }
                        break;
                    }
                } ?>
            </span>
            <a class="clearAll">Clear All</a>
        </div>
    <?php } ?>
</div>
<style type="text/css">
    .strikeThrough{
        text-decoration: line-through;
    }
</style>