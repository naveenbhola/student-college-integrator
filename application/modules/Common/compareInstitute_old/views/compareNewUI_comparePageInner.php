<table class="cmpre-table" id="compareTable" width="100%" cellpadding="0" cellspacing="0">
<tbody>
  <tr>
    <td style="padding-top: 15px;">
       <div class="cmpre-head">&nbsp;</div>
    </td>
    <script>
      var emailDataArray  = new Array();
      var cookieDataArray = new Array();
    </script>
    <?php 
    $j = 0; $isSAComparePage = 0;
    foreach($institutes as $institute){
      $j++;
      $course = $institute->getFlagshipCourse();
      $course->setCurrentLocations($request);
      if(strlen($institute->getName()) > 100){
        $instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($institute->getName()));
        $instStr .= "...";
      }else{
        $instStr = html_escape($institute->getName());
      }
      if($_COOKIE["applied_".$course->getId()] == 1){
        $className = "requested-e-bro";
      }else{
        $className = "";
      }
      ?>
      <script>
        var stringTemp = '<?=$course->getInstId()?>::<?=$course->getId()?>::<?=html_escape($institute->getName())?>::<?php echo $course->getCurrentMainLocation()->getCity()->getId();?>::<?=$course->getCurrentMainLocation()->getLocality()->getId()?>';
        emailDataArray.push(stringTemp);
        var cookieValTemp = "<?=$institute->getId().'::'.$course->getId().'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'').'::'.html_escape($institute->getName()).', '.$course->getCurrentMainLocation()->getCity()->getName().'::'.$course->getId().'::'.$course->getURL()?>";
        cookieDataArray.push(cookieValTemp);
        updateTrackingPageKey('<?=$course->getId();?>');
      </script>
      <?php 
    ?>
    <td style="padding-top: 15px;">
      <div class="cmpre-head">
        <a class="close-sec" href="javascript:;"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?=$j?>','COMPARE_DESK_REMOVE_NONE_STICKY');"></i></a>
        <div class="cmpre-inst-title">
          <a href="<?=$course->getURL()?>" target="_blank" title="<?=htmlspecialchars($institute->getName())?>"><?=$instStr?></a>
          <p class="color-of-year"><?php if($institute->getEstablishedYear()){ echo "Established in: ".$institute->getEstablishedYear();}?></p>
        </div>
        <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?=$course->getCurrentMainLocation()->getCity()->getName()?></p>
        <?php 
        if($brochureURL->getCourseBrochure($course->getId())){
          if($className == "requested-e-bro"){
            ?>
            <p class="eb-sent">E-brochure Sent</p>
            <?php 
          }else{
            ?>
            <div id="reb_button_<?php echo $course->getId()?>">
              <a href="javascript:;" class="new-dwn-btn" value="Download E-brochure" onclick="ApplyNowCourse('<?php echo $institute->getId(); ?>','<?php echo base64_encode(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()));?>','<?php echo $course->getId(); ?>','<?php echo base64_encode(htmlspecialchars($course->getName())); ?>','<?=$course->getURL()?>',210);"><i class="cmpre-sprite ic-ebrocher"></i>Download E-Brochure</a>
            </div>
            <?php 
          }
        }
        ?>
      </div>
    </td>
    <?php 
    }
    $allowAutoSuggest = true;
    if($j<4){
      while ($j < 4){
        $j++;
        ?>
        <td style="padding-top: 15px;" <?php if($allowAutoSuggest){?>id="newInstituteSection"<?php }?>>
          <div class="cmpre-head">
            <div class="add-inst-number"><?=$j?></div>
            <div class="add-simlar-clgs addSimilarCollege" id="addSimilarCollege<?=$j?>">
              <div id="keywordSuggest_normalDiv">
              <input type="text" class="find-txtfield <?php echo !$allowAutoSuggest?'diable-click':''?>" name="add-college" placeholder="Find College" id="keywordSuggest" onfocus="toggleBoxText(this, 'focus'); this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="toggleBoxText(this, 'blur'); this.hasFocus=false; checkTextElementOnTransition(this,'blur');"/>
              </div>
              <span id="<?php echo $allowAutoSuggest?'searchIconInBox':''?>">
                <i class="cmpre-sprite ic-srch opacity"></i>
              </span>
              <div class="cmpre-sugstr-box" id="suggestions_container_normalDiv">
                <ul id="suggestions_container"></ul>
              </div>
              <?php 
              if(isset($institutesRecommended) && count($institutesRecommended)>0 ){
                if($allowAutoSuggest){
                  $classButton = "new-gray-btn";
                }
                else{
                  $classButton = "new-dis-btn";
                }
              ?>
                <p class="or-txt">Or</p>
                <a href="javascript:;" onclick="<?php if($allowAutoSuggest){?>$j('#recommendationDiv<?=$j?>').toggle();<?php }?>" class="<?php echo $classButton;?>">Add Similar Colleges</a>
              <?php 
                $this->load->view('receommendations',array('keyVal'=>$j));
              }
              ?>
            </div>
          </div>
        </td>
        <?php 
        $allowAutoSuggest = false;
      }
    }
    ?>
  </tr>
  <!--course Name-->
  <tr id="courseDisplayDiv" <?php if(!($institutes && count($institutes)>0)){ echo "style='display:none;'";} ?>>
      <td>
          <div class="cmpre-head">
             <label class="t">Course Name</label>
          </div>
      </td>
      <?php 
      $j = 0; $k = 0;
      foreach($institutes as $institute){
        $k++;
        $course = $institute->getFlagshipCourse();
        $courseId = $course->getId();
        if( isset( $courseLists[$courseId] ) && count($courseLists[$courseId])>0 ){
          $j++;
      ?>
          <td>
              <div class="cmpre-head">
                  <select class="custom-select" style="display:none;" id="courseSelect<?=$j?>">
                  <?php
                  $selectedCourse = 'Select Course';
                  foreach ($courseLists[$courseId] as $courseD){
                    $selected = "";
                    if($courseD['course_id']==$courseId){
                      $selected = "selected='selected'";
                      $selectedCourse = $courseD['courseTitle'];
                    }
                    ?>
                    <option title="<?=$courseD['courseTitle']?>" value='<?=$courseD['course_id']?>' <?=$selected?> ><?=$courseD['courseTitle']?></option>
                  <?php 
                  }
                  ?>
                  </select>
                  <div class="cmpre-drpdwn">
                       <a href="javascript:;" class="customCourse" courseTupple="<?php echo $j?>" id="customCourse<?php echo $j?>"><i class="cmpre-sprite ic_arrow"></i><span class="display-area"><?php echo substr($selectedCourse, 0,21)?></span></a>
                       <div class="custm-drp-layer" id="customCourseList<?php echo $j?>">
                            <ul>
                            <?php 
                            foreach ($courseLists[$courseId] as $courseD){
                              ?>
                              <li courseId="<?php echo $courseD['course_id']?>" courseTupple="<?php echo $j?>"><a href="javascript:;"><?php echo $courseD['courseTitle']?></a></li>
                              <?php 
                            }
                            ?>
                            </ul>
                       </div>
                  </div>
              </div>
          </td>
      <?php 
        }else{
          ?>
          <td>
            <strong style="font-size:22px; color:#babbbd">-</strong>
          </td>
          <?php 
        }
      }
      if($k < 4){
        for ($x = $k+1; $x <=4; $x++){
          echo '<td id="newCourseSection"></td>';
        }
      }
      ?>
  </tr>
  <?php
  //We will have to check the Category of the First course. If it is MBA, we will load another view
  if(($institutes && count($institutes)>0)){
    //if($showMBA){
      $this->load->view('compareNewUI_compareFieldsMBA');
    /*}
    else{
      $this->load->view('compareNewUI_compareFieldsDefault');
    }*/
    $this->load->view('compareNewUI_compareCollegeFacilities');
    $this->load->view('compareNewUI_collegeReviewWidget');
    $this->load->view('compareNewUI_campusRepWidget');
  }
  ?>
</tbody>
</table>