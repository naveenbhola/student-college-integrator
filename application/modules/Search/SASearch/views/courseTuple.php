<?php if(count($univData > 0)){

    if(count($univData['courseSliderData'])>0)
    {
        ?>
        <script>
            var courseSlderJSON = '<?php echo base64_encode(json_encode($univData['courseSliderData']));?>';
        </script>
        <?php
    }
    $courseSliderData = $univData['courseSliderData'];
    unset($univData['courseSliderData']);
    foreach($univData as $univId => $courseData){ ?>
        <?php
        $firstCourseId = reset(array_keys($courseData));
        $firstCourseData = $courseData[$firstCourseId];
        
        $isShortlistedCourse = (in_array($firstCourseId, $shortListedCourses['courseIds']) == true) ? true : false;
        $videoCount; $photoCount;
        if(isset($universityRelatedData) && ($universityRelatedData[$univId] instanceof University))
        {
            $videoCount = $universityRelatedData[$univId]->getVideoCount();
            $photoCount = $universityRelatedData[$univId]->getPhotoCount();
        }
        $compareDataChekedStr = (in_array($firstCourseData['a'],$userComparedCourses) == true) ? 'checked':'';
        $desiredCourseId = $firstCourseData['ae'];
        $level = getCourseLevel($firstCourseData['t']);
        $brochureDataObj = array(
            'sourcePage'       				  => 'abroadSearch',
            'courseId'         				  => $firstCourseData['a'],
            'courseName'       				  => $firstCourseData['i'],
            'universityId'     				  => $univId,
            'universityName'   				  => $firstCourseData['d'],
//            'userStartTimePrefWithExamsTaken' => $userStartTimePrefWithExamsTaken,
            'destinationCountryId'			  => $brochureDataArray[$univId]['destinationCountryId'],
            'destinationCountryName'		  => $firstCourseData['e'],
//            'courseData'       				  => base64_encode(json_encode($courseData)), // send data now required for new single registration form
//            'userDesiredCourse'				  => $userStartTimePrefWithExamsTaken['desiredCourse'],
            'widget'                          => 'search',
            'trackingPageKeyId'               =>  33
        );
        $rmcObject = $brochureDataObj;
        $rmcObject['trackingPageKeyId'] = 361;
        $rmcObject['userRmcCourses'] = $userRmcCourses;
        $rmcObject['class'] = 'tl';
        $rmcObject['loc'] = 'rmcbut';
        unset($courseData[$firstCourseId]);
        $countPropShow =0;
        $flagFee = false;
        $flagExam = false;
        $flag12th = false;
        $flagBachelors = false;
        $flagWE = false;
        if(isset($firstCourseData['o']))
        {
            $flagFee =true;
            $countPropShow++;
        }
        if(isset($firstCourseData['examString']))
        {
            $flagExam =true;
            $countPropShow++;
            $moreExams = ($courseSliderData[$univId][$firstCourseId]['cME']);
            $moreExamStr = isset($moreExams) ? ' <span class="mrExm">+'.count($moreExams).' more</span>' : '';
        }
        if(isset($firstCourseData['otherFields']['t']) && $level == STUDY_ABROAD_BACHELORS)
        {
            $flag12th =true;
        }
        if(isset($firstCourseData['otherFields']['bP']) && $level == STUDY_ABROAD_MASTERS)
        {
            $flagBachelors =true;
        }
        if($flagBachelors || $flag12th)
        {
            $countPropShow++;
        }
        if(isset($firstCourseData['otherFields']['wE']) && $desiredCourseId == DESIRED_COURSE_MBA)
        {
            $flagWE =true;
            $countPropShow++;
        }
        ?>
        <?php
          if(strpos($firstCourseData['ad'], 'univDefault') !== false){
            $firstCourseData['ad'] = getUnivDefaultImageBySize($firstCourseData['ad'], '300x200');
          }
        ?>
<div class="newTupleDiv clearwidth">
  <div class="clearwidth">
    <div class="picDiv <?php echo(($videoCount>0 || $photoCount > 0)?'':'noShadow'); ?>">
      <a class="tl" loc="img" lid="<?php echo $firstCourseData['ai']; ?>" id="<?php echo 'hrefI_'.$univId;?>" href="<?php echo $firstCourseData['c']; ?>"><img src="<?php echo $firstCourseData['ad'];?>" height="200" width="300" alt="<?php echo htmlentities($firstCourseData['d']);?>"></a>
        <?php if($videoCount>0 || $photoCount > 0){ ?>
      <p class="clickIcons">
            <?php
            if($videoCount > 0)
            {
                ?>
        <a class="selfi_imgs"><i class="srpSprite ivideo"></i><?php echo $videoCount ?></a>
                <?php
            }
            if($photoCount > 0)
            {
                ?>
        <a class="selfi_imgs"><i class="srpSprite icamera"></i><?php echo $photoCount ?></a>
                <?php
            }
            ?>
      </p>
            <?php
        }
        ?>
    </div>
    <div class="selectedDtls">
      <div class="nameDiv">
        <h2 class="titleOfClg"><a class="tl" loc="utitle" lid="<?php echo $firstCourseData['ai']; ?>" id="<?php echo 'href_'.$univId;?>" href="<?php echo $firstCourseData['c'];?>"><?php echo $firstCourseData['d'];?></a></h2>
        <p class="subPlace"><?php echo $firstCourseData['g'].', ';?><?php echo $firstCourseData['e'];?><span>|</span>  <?php echo ucfirst($firstCourseData['h']);?></p>
      </div>
      <?php
      if($countPropShow == 4){
        if(!empty($moreExamStr)){
          $height = '228px';
        }else{
          $height = '205px';
        }
      }else{
        if(!empty($moreExamStr)){
          $height = '180px';
        }else{
          $height = '157px';
        }
      }
      ?>
      <!-- <div class="clearwidth dataDiv" style="height: <?php// echo $height;?>"> -->
      <div class="clearwidth dataDiv">
          <div class="clearwidth slideBox fade slide--up slide-in active <?php echo 'uId_'.$univId;?>">
              <div class="courseDiv">
                  <h3 class="itCourse tl" loc="ctitle" lid="<?php echo $firstCourseData['a']; ?>" ><?php echo $firstCourseData['i'];?></h3>
                  <p class="subPlace"><?php echo htmlentities($firstCourseData['j'])." ".(($firstCourseData['j']==1)?str_replace('s', '', $firstCourseData['k']):($firstCourseData['k']));?>  <span>|</span>  <?php echo $firstCourseData['t']." ".(($firstCourseData['t']=='Masters' || $firstCourseData['t']=='Bachelors')?" Degree":"");?></p>
                  <div class="flexBox">
                      <?php if($flagFee){?>
                          <div>
                              <p class="flexDtls">1st Year Total Fees</p>
                              <p class="costDiv"><?php echo htmlentities($firstCourseData['o']);?></p>
                          </div>
                      <?php }?>
                      <?php if($flagExam){?>
                          <div>
                              <p class="flexDtls">Exams Accepted</p>
                              <p class="costDiv"><?php echo htmlentities($firstCourseData['examString']).$moreExamStr;?></p>
                          </div>
                      <?php }?>
                      <?php if($flag12th){?>
                          <div>
                              <p class="flexDtls">Class 12th marks</p>
                              <p class="costDiv"><?php echo htmlentities($firstCourseData['q']);?> % </p>
                          </div>
                      <?php }?>
                      <?php if($flagBachelors){?>
                          <div>
                              <p class="flexDtls">Bachelor marks</p>
                              <p class="costDiv"><?php echo htmlentities($firstCourseData['bachelorString']); ?></p>
                          </div>
                      <?php }?>
                      <?php if($flagWE){?>
                          <div>
                              <p class="flexDtls">Work Experience</p>
                              <p class="costDiv"><?php echo htmlentities(($firstCourseData['s'])==0)?"Required":$firstCourseData['s'];?> <?php echo (($firstCourseData['s'] ==1)?"year":($firstCourseData['s']!=0 && $firstCourseData['s']!='')?"years":'');?></p>
                          </div>
                      <?php }?>
                  </div>
              </div>
              <?php
              $today = date("Y-m-d");
              if($firstCourseData['aS']['text'] && $today >= $firstCourseData['aS']['startdate'] && $today <= $firstCourseData['aS']['enddate']) {
                ?>
              <div class="Annoncement-Box">
              <strong>Annoncement</strong>
              <div class="Announcement-section">
              <p> <?php echo $firstCourseData['aS']['text'] ?></p>
              <p><?php echo $firstCourseData['aS']['actiontext']?></p>
              </div>
              </div>
            <?php } ?>
              <div class="moreSelections clearwidth">
                  <div class="setDiv">
              <span class="cssChck">
                <input class="tl" loc="compare" lid="<?php echo $firstCourseData['a']; ?>" type="checkbox" name="compare<?php echo $firstCourseId ?>" id="compareSearch<?php echo $firstCourseId ?>" onclick="addRemoveFromCompare('<?php echo $firstCourseId ?>',552, true);" <?php echo ($compareDataChekedStr == 'checked'?'checked="checked"':''); ?> >
                <label for="compareSearch<?php echo $firstCourseId ?>" >
                    <p><?php echo ($compareDataChekedStr == 'checked'?'Added':'Add') ?> to compare</p>
                </label>
              </span>
                      <span loc="save" lid="<?php echo $firstCourseData['a']; ?>" class="startSelect tl <?php echo ($isShortlistedCourse == true ? 'active':'')  ?>" onClick = "SASearchV2Obj.addRemoveFromShortlistedCourseNew(<?php echo $firstCourseId ?>,1729)" id="shrtList<?php echo $firstCourseId?>"> <i class="srpSprite iStar"></i> <?php echo($isShortlistedCourse == true ? 'Saved':'Save')  ?></span>
                  </div>
                  <div class="flt_right lineNone">
                     <?php echo $rateMyChanceCtlr->loadRateMyChanceButton($rmcObject, 'abroadSearchV2'); ?>
                      <a loc="ebbut" lid="<?php echo $firstCourseData['a']; ?>" href="javascript:void('0');" class="dwnLdBtn tl" onclick="loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>');">Download Brochure</a>
                  </div>
              </div>
          </div>
      </div>
        <div class="moreExamDiv" id="moreExam_<?php echo $univId;?>">
            <ul class="moreExamList">
                <?php
                if (isset($moreExams))
                {
                    foreach ($moreExams as $val)
                    {
                        ?>
                        <li>
                            <?php echo $val;?>
                        </li>
                        <?php

                    }
                }
                ?>
            </ul>
        </div>
    </div>
  </div>
  <?php if(count($courseSliderData[$univId]) >0){ $this->load->view('SASearch/moreCourseSlider',array('univId'=>$univId,'courseData'=>$courseSliderData[$univId])); }?>
</div>
    <?php } ?>
<?php } ?>
