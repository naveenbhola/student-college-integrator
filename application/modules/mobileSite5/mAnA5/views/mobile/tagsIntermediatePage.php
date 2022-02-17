         <p class="tag-titl">Tags associated to this <span class="qdTitleHead lowerCaseClass">question</span></p>
         <div class="check-col">
          <?php
          if(empty($tagsData)){
            echo "<span id='no_tag_div' style='font-size:10px;'>Select tags that describe your $entityType</span>";
          }
          ?>
            <ul class="checkbox-ul" id="tags-ana-post">
              <?php
              foreach ($tagsData as $key => $value) {
                ?> 
                <li class="">
                 <div class="check-ip">
                   <input type="checkbox" name="education[]" tagId ="<?php echo $value['tagId']?>" classification="<?php echo $value['classification']?>" id="check_<?php echo $value['tagId']?>" <?php if($value['checked']) echo "checked";?>/>
                   <label for="check_<?php echo $value['tagId']?>" ><?=$value['tagName'];?></label> 
                 </div>
               </li>
                <?php
              }
              ?>
            
            </ul>
         </div>
        
          <div class="input-box"> 

              <?php if($entityType == "question"){
                  ?>
                  <a href="#addMoreTagsPostLayer" data-inline="true" data-rel="dialog" data-transition="fade" onclick="sendGAWebAnA('QUESTION POSTING','ADDTAGS_QUESTIONPOSTING_WEBAnA');setFocusElementId('tagSearch');" id="addMoreTagLayer">
                  <?php
              } else {
                  ?>
                  <a href="#addMoreTagsPostLayer" data-inline="true" data-rel="dialog" data-transition="fade" onclick="sendGAWebAnA('DISCUSSION POSTING','ADDTAGS_DISCUSSIONPOSTING_WEBAnA');setFocusElementId('tagSearch');" id="addMoreTagLayer">
                  <?php
              }
              ?>
              
               <input type="text" class="add-tags" placeholder="Add more tags" />
               <i class="search-ico"></i>
             </a>
          </div>
        