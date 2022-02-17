<table border="0" cellpadding="0" cellspacing="0" id="tablescrollTop" class="compare-head-table">
<tr id="compHeader">
    <th width="25%" style="vertical-align:bottom"><div class="compare-detail-content"><strong>University name</strong></div></th>
    <?php /************************* main courses to compare*************************************/?>
    <?php foreach ($courseDataObjs as $courseObj) { 
         $univId            = $courseObj->getUniversityId();
         $universityObject  = $univDataObjs[$univId];
         $univPhotos        = $universityObject->getPhotos();           

        if(count($univPhotos))
        {
            $imgUrl = $univPhotos['0']->getThumbURL('172x115');
        }
        else 
        {
            $imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
        } ?>
    <input type="hidden" name="courseid" id="courseid"></div>
    <input type="hidden" name="source" id="source"></div>
    <th width="25%">
        <div class="compare-img" id="compare-img-1">
            <img src="<?php echo $imgUrl?>" alt="<?php echo htmlentities($courseObj->getUniversityName());?>" width="172" height="114"/>
            <a href="javascript:void(0);" class="compare-remove" id="compare-remove-1" onclick="removeCourseFromComparePage('<?php echo htmlentities($courseObj->getId());?>')">&times;</a>
        </div>
        <a href="<?php echo $universityObject->getURL();?>" class="compared-univ-name"><?php echo htmlentities($courseObj->getUniversityName());?></a>
    </th>
    <?php } ?>                    
    <?php if($coursesCount == 1){?>
     <th width="25%"></th>
    <?php } ?>
    <?php if($coursesCount < 3){?>
    <th width="25%">
        <?php if(count($recommendedCourses)>0){ ?>
        <h2 class="comp-similar-text">Compare with similar colleges</h2>
        <ul class="compare-suggestion-list">
            <?php $this->load->view('compareCourses/widgets/recommendedCoursesForCompare'); ?>
        </ul>
        <?php } ?>
    </th>
    <?php } ?>
</tr>
<?php if(!empty($courseDataObjs)) { ?>
<tr>
    <td><div class="compare-detail-content"><strong>Course name</strong></div></td>
    <?php foreach ($courseDataObjs as $courseObj) { ?>
    <td><a href="<?php echo $courseObj->getURL(); ?>"><?php echo  htmlentities($courseObj->getName()); ?></a></td>
    <?php } ?>
    <?php if($coursesCount == 1){?>
    <td></td>
    <td></td>
    <?php } else if($coursesCount == 2){?>
    <td></td>
    <?php } ?>
</tr>
 <tr>
    <td><div class="compare-detail-content"><strong>Location</strong></div></td>
    <?php foreach ($courseDataObjs as $courseObj) { ?>
    <td><?php $cityName = $courseObj->getCityName();
              $countryName = $courseObj->getCountryName();
        if(!empty($cityName)|| !empty($countryName) ){?>
        <?php echo htmlentities($courseObj->getCityName()).", "; echo htmlentities($courseObj->getCountryName()); ?>
        <?php } else { echo "------"; } ?>
    </td>
    <?php } ?>
    <?php if($coursesCount == 1){?>
    <td></td>
    <td></td>
    <?php } else if($coursesCount == 2){?>
    <td></td>
    <?php } ?>
</tr>
<?php } ?>
</table>