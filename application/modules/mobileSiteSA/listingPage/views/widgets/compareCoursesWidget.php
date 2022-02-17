<section class="detail-widget">
  <div class="detail-widegt-sec">
    <div class="detail-info-sec new__widget">
      <strong>Compare this course with similar course</strong>
      <div class="widget__list">
         <p class="Big__ltrs">This Course</p>
         <ul>
           <li>
             <div class="">
               <div class="img__col">
                  <img src="<?=$imgURL?>" alt="">
               </div>
               <div class="widget__sec">
                  <p class="univ__titl"><?= $courseObj->getUniversityName().", "?>  <span class="univ__loc"><?=$universityObj->getLocation()->getCountry()->getName();?></span></p>
                  <p class="univ__spec"><?= $courseObj->getName();?></p>
                  <p class="univ__fee">1<sup>st</sup> year Fees: <?=$courseFeesDisplableAmount?></p>
               </div>
             </div>
           </li>
           <li>
             <div class="">
               <div class="img__col">
                  <img src="<?=$recommendedCompareCourseData['universityImageURL']?>" alt="">
               </div>
               <div class="widget__sec">
                  <p class="univ__titl"><?=$recommendedCompareCourseData['universityName'].", "?> <span class="univ__loc"><?=$recommendedCompareCourseData['countryName'];?></span></p>
                  <p class="univ__spec"><?=$recommendedCompareCourseData['courseName']?></p>
                  <p class="univ__fee">1<sup>st</sup> year Fees: <?=$recommendedCompareCourseData['courseFees']?></p>
               </div>
             </div>
           </li>
         </ul>
         <a href="javascript:void(0);" class="btn btn-primary btn-full ui-link" id="compareWidgetButton" compareWidgetTrackingId=1249 entityIds='<?=$compareData["compareCourseIds"]?>'>Compare with similar course</a>
      </div>
    </div>
  </div>
</section>