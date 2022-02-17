<div class="main-menu cf" id="mypanel">
    <div class="lgn-section">
        <ul>
            <?php 
            $counter = 1;
            foreach ($gnbData as $groupName => $groupBucket) {?>
            <li>
                  <label for="sm<?=$counter?>" class="menuTab-div fnt-wt melabel"><?php echo $groupName;?>
                      <span class="drop-icon"></span>
                    <label title="Toggle Drop-down" class="drop-icon" for="sm<?=$counter?>"></label>
                  </label>
                  <input type="radio" id="sm<?=$counter?>" name="tabMenu">
                  <div class="sub-menu mediv">
                      <div class="drpdwn-lft">
                      <?php foreach ($groupBucket['left'] as $mainSectionHeading => $mainSectionBucket) {?>                  
                        <?php echo generateSubMenu($mainSectionBucket,$mainSectionHeading,$groupName);?>
                      <?php }?>
                      </div>

                      <div class="drpdwn-Rgt">
                          <ul>
                          <?php foreach ($groupBucket['right'] as $mainSectionHeading => $mainSectionBucket) {?>                  
                              <?php if(!in_array($mainSectionHeading,array('TOEFL','GRE','SAT'))) {
                                  $liClass = '';
                                  if(in_array($mainSectionHeading,array('Find Colleges by Exam','Scholarships','Education Loans'))){
                                      $liClass = "class='clgByExam'";
                                  }
                                ?>
                              <li <?=$liClass?> >
                              <?php } ?>
                              <?php echo generateSubMenu($mainSectionBucket,$mainSectionHeading,$groupName);?>
                              <?php if(!in_array($mainSectionHeading,array('IELTS','PTE','GMAT'))) {?>
                              </li>
                              <?php } ?>
                          <?php }?>
                          </ul>
                      </div>                      
                  </div>    
            </li>
            <?php 
                  $counter++;
                  } ?>                  
        </ul>
    </div>
    <div class="full-screen-close"></div>
</div>      