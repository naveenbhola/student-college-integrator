<tr>
    <td style="padding-top: 15px;">
       <div class="cmpre-head">&nbsp;</div>
    </td>
    <?php 
    $j = 0; $isSAComparePage = 0;
    foreach($courseIdArr as $courseId){
      $j++;
      $course      = $courseObjs[$courseId];
      if($showAcademicUnitSection){
        $instituteId = $academicUnitRawData[$courseId]['userSelectedInstitute'];
      }else{
        $instituteId = $instIdArr[$courseId];
      }
      $institute   = $instituteObjs[$instituteId];
      $instNameDisplay = ($institute->getShortName() != '') ? $institute->getShortName() : $institute->getName();
      if(strlen($instNameDisplay) > 100){
        $instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($instNameDisplay));
        $instStr .= "...";
      }else{
        $instStr = html_escape($instNameDisplay);
      }
    ?>
    <td style="padding-top: 15px;">
      <div class="cmpre-head">
        <a class="close-sec" href="javascript:void(0);"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?php echo $j;?>','COMPARE_DESK_REMOVE_NONE_STICKY');"></i></a>
        <div class="cmpre-inst-title">
          <a href="<?php echo $course->getURL();?>" target="_blank" title="<?php echo htmlspecialchars($institute->getName());?>"><?php echo $instStr;?></a>
          <p class="color-of-year"><?php if($institute->getEstablishedYear()){ echo "Established in: ".$institute->getEstablishedYear();}?></p>
        </div>
        <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?php echo ((is_object($institute->getMainLocation()))?$institute->getMainLocation()->getCityName():'')?></p>
        <?php 
	if($checkForDownloadBrochure[$course->getId()]){
            $this->load->view('downloadBrochure',array('courseId'=>$courseId, 'trackingKeyId' => 210));
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
            <div class="add-inst-number"><?php echo $j;?></div>
            <div class="add-simlar-clgs addSimilarCollege" id="addSimilarCollege<?php echo $j;?>">
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
              if(count($courseIdArr) > 0){
                if($allowAutoSuggest){
                  $classButton = "new-gray-btn";
                }
                else{
                  $classButton = "new-dis-btn";
                }
              ?>
                <p class="or-txt reco-or" style="display:none;">Or</p>
                <a href="javascript:void(0);" style="display:none;" onclick="<?php if($allowAutoSuggest){?>$j('#recommendationDiv<?php echo $j;?>').toggle();<?php }?>" class="reco-link <?php echo $classButton;?>">Add Similar Colleges</a>
              <?php 
                echo '<div id="reco-div-wrap-'.$j.'">';
                //$this->load->view('receommendations',array('keyVal'=>$j));
                echo '</div>';
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
