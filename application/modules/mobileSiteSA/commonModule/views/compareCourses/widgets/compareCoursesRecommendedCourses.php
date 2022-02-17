<table border="0" cellpadding="0" cellspacing="0" id="tablescrollTop">
    <tr style="border-bottom:1px solid #aaa">
        <?php foreach ($courseDataObjs as $courseObj) {
         $univId            = $courseObj->getUniversityId();
         $universityObject  = $univDataObjs[$univId];
         $univPhotos        = $universityObject->getPhotos();           

         if(count($univPhotos)) { $imgUrl = $univPhotos['0']->getThumbURL('172x115');  }
         else { $imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";} ?>
        <th style="width:50%; border-bottom:none;">
            <div class="compare-detail-content">
                <div class="SA-compare-fig">
                    <img src="<?php echo $imgUrl?>" alt="<?php echo htmlentities($courseObj->getUniversityName());?>" width="140" height="98" alt="compare-ins">
                    <a href="javascript:void(0);" onclick="removeCourseFromComparePage('<?php echo htmlentities($courseObj->getId());?>')" class="compare-remove-mark">&times;</a>
                </div>
                <p><a href="<?php echo $universityObject->getURL();?>"><?php echo htmlentities($courseObj->getUniversityName());?></a></p>
            </div>
        </th>
        <?php }
        if(count($courseDataObjs)==1){
			if($recommendedCourses && count($recommendedCourses)>0){ ?>
        <th style="width:50%; border-bottom:none;">
            <div class="compare-detail-content">
                <div class="similar-compare-sec">
					<a class="fnd-btn" id="compareSearchContainerLink" href="#compareSearchContainer" data-rel="dialog" data-transition="slide"><i class="sprite src-headIcn"></i>Find a college</a>
					<span class=or-txt>OR</span>
                    <h2 class="similar-compare-title">Compare with similar</h2>
                    <ul class="similar-list">
                        <?php $this->load->view('commonModule/compareCourses/widgets/recommendedCourseListForCompare'); ?>
                    </ul>
                 </div>
            </div>
        </th>
        <?php }else{ // blank add another case ?>
                <th id="lastCellDiv" style="vertical-align:middle;font-size:11px;color:#999;width:50%; border-bottom:none">
					<div class="compare-detail-content">
						<div class="similar-compare-sec">
							<a class="fnd-btn" id="compareSearchContainerLink" href="#compareSearchContainer" data-rel="dialog" data-transition="slide"><i class="sprite src-headIcn"></i>Find a college</a>
						 </div>
					</div>
                </th>
				<?php }
		} ?>
    </tr>
    <tr>
        <td colspan="2">
            <div class="compare-detail-content">
                <strong>Course Name</strong>
            </div>
        </td>
    </tr>
    <tr class="courseSelectRow">
        <?php $counter=0;$revCounter=1;
			$serialObjs = array_values($courseDataObjs);
			foreach ($courseDataObjs as $courseDataObj) { ?>
        <td>
		<div class="compare-detail-content">
			<?php
					$additionalData = array('courseList'=>$allCoursesByUnivs[$courseDataObj->getUniversityId()],
															'currentCourse'=>$serialObjs[$revCounter]);
					if($counter == 1)
					{
						$additionalData['secondCourse'] = true;
					}
					$this->load->view('commonModule/compareCourses/widgets/compareCourseDropdown',$additionalData);
					$counter++;
					$revCounter--;
			?>
			<input type="hidden" id="addedCourse" value="">
			<input type="hidden" id="removedCourse" value="">
		</div>
		</td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php }?>
    </tr>
    <tr>
        <td colspan="2"><div class="compare-detail-content"><strong>Location</strong></div></td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td class="locationCell"><?php $cityName = $courseObj->getCityName();
              $countryName = $courseObj->getCountryName();
              if(!empty($cityName)|| !empty($countryName) ){?>
              <div class="compare-detail-content"><p><?php echo htmlentities($courseObj->getCityName()).", "; echo htmlentities($courseObj->getCountryName()); ?></p></div></td>
              <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        <?php } ?>
        <?php if($coursesCount == 1){?><td class="locationCell"></td><?php }?>
    </tr>
</table>