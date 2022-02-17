<!--course Name-->
  <tr id="courseDisplayDiv" <?php if(!($courseIdArr && count($courseIdArr)>0)){ echo "style='display:none;'";} ?>>
      <td>
          <div class="cmpre-head">
             <label class="t">Course Name</label>
          </div>
      </td>
      <?php 
      $j = 0; $k = 0;
      foreach($courseIdArr as $courseId){
        $k++;
        if(count($courseIdArr) > 0){
          $j++;
      ?>
          <td>
              <div class="cmpre-head">
                  <div class="cmpre-drpdwn">
                       <a href="javascript:void(0);" class="customCourse" data-course="<?php echo $courseId;?>" courseTupple="<?php echo $j?>" id="customCourse<?php echo $j?>"><i class="cmpre-sprite ic_arrow"></i><span class="display-area" title="<?=$courseNameArr[$courseId]?>"><?php echo substr($courseNameArr[$courseId], 0,21)?></span></a>
                       <div class="custm-drp-layer" id="customCourseList<?php echo $j?>">
                            <ul>
                            <li><a href="javascript:;">Loading List...</a></li>
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