<h2 class="popular-title">Most popular <?php echo $courseNames['mainCourseId']; ?> colleges</h2>
<div class="clearfix"></div>
<?php $data = $coursesData[$mainCourseId]['widgetData']['countryHomePopularUniversities']; ?>
<div id="sliderWrapper" class="cHomePopularCourseSlider" style="position:relative; height:336px;overflow-x: hidden;">
<ul class="popular-course-slider" id="sliderUl" style="position:absolute;">
    <?php foreach($data['universityData'] as $univData){ ?>
        <li class="swipetuple">
            <div class="popularCrse-detail grey-bg">
                <a href="<?php echo $univData['url'];?>"><p class="univrsty-title"><?php echo formatArticleTitle($univData['university_name'],52); ?></p></a>
                <span><?php echo $univData['cityName']==""?"":$univData['cityName'].", "?><!--<?php echo $univData['stateName']==""?"":$univData['stateName'].", "?>--><?php echo $univData['country']==""?"":$univData['country']?></span>
                <div class="poplrUniv-img"><a href="<?php echo $univData['courseLink'];?>"><img src="<?php echo $univData['photos'];?>" width="172" height="115" alt="univ-img"></a> </div>
            </div>
            <div class="popularCrse-detail white-bg">
                <a href="<?php echo $univData['courseLink'];?>" style="display:inline-block;"><?php echo formatArticleTitle($univData['courseName'],50);?></a>
                <div class="popularCrse-info">
                    <p>
                        <label>1st year total fees:</label>
                        <strong><?php echo $univData['courseFee'];?></strong>
                    </p>
                    <p class="last">
                        <label>Eligibility:</label>
                        <strong><?php echo $univData['courseExams'][0].($univData['courseExams'][1]==""?"":", ".$univData['courseExams'][1]); ?></strong>
                    </p>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>
</div>
<div class="clearfix"></div>
<a href="<?php echo $data['viewAllUniversityPageUrl'];?>" class="viewAll-link"><strong>View <?php echo $data['totalCount']==1?"1 university":"all ".$data['totalCount']." universities";?> in <?php echo $countryObj->getName();?> &gt;</strong></a>
