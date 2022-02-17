<div id="mmm-cont" style="margin:0 auto;">
  <div id="select_user_set_loader"></div>
  <div id="select_user_set">
    
    <ul style="display:none;" id="userset_template_<?php echo $mailCriteria?>">
      <li>
        <label>Userset:</label>
        <div class="flLt">
          <select class="width-200 drip--fileds">
            <option value=0>Select</option>
            <?php
              foreach($usersets as $key=>$name){
                                                                if($key == 3685) continue;
                echo "<option value=".$key.">".$name."</option>";
              }
            ?>
          </select>&nbsp;&nbsp;
          <input type="button" onclick="addNewOrset(this,<?php echo $mailCriteria?>);" value="Or">
        </div>
      </li>
    </ul>

    <h4><span>Add Userset<span class="redText">*</span></span></h4>

    <div id="profile-form_<?php echo $mailCriteria?>-0">
      <ul class="profile-form" id="profile-form_<?php echo $mailCriteria?>-0-0">
        <li id="userset_<?php echo $mailCriteria?>-0-0-0">
          <label>Userset:</label>
          <div class="flLt">
            <select class="width-200 drip--fileds" name="userset_<?php echo $mailCriteria?>[0][0][]">
              <option value=0>Select</option>
              <?php
                foreach($usersets as $key=>$name){
                                                                        if($key == 3685) continue;
                  echo "<option value=".$key.">".$name."</option>";
                }
              ?>
            </select>&nbsp;&nbsp;
            <input type="button" id="or_<?php echo $mailCriteria?>-0-0-0" onclick="addNewOrset(this,<?php echo $mailCriteria?>);" value="Or">
          </div>
        </li>
      </ul>
    </div>
   <!--  <ul class="profile-form_<?php //echo $mailCriteria?>" id="profile-and_<?php // echo $mailCriteria?>-0">
      <li>
        <label>&nbsp;</label>
      </li>
    </ul> -->
    <div class="clearFix spacer10"></div>
    <h4>Exclude Userset</h4>
    <div id="profile-form_<?php echo $mailCriteria?>-1">
      <ul class="profile-form" id="profile-form_<?php echo $mailCriteria?>-1-0">
        <li id="userset-1-0-0">
          <label>Userset:</label>
          <div class="flLt">
            <select class="width-200 drip--fileds" name="userset_<?php echo $mailCriteria?>[1][0][]">
              <option value=0>Select</option>
              <?php
                foreach($usersets as $key=>$name){
                                                                        if($key == 3685) continue;
                  echo "<option value=".$key.">".$name."</option>";
                }
              ?>
            </select>&nbsp;&nbsp;
            <input type="button" id="or_<?php echo $mailCriteria?>-1-0-0" onclick="addNewOrset(this,<?php echo $mailCriteria?>);" value="Or">
          </div>
        </li>
      </ul>
    </div>
<!--     <ul class="profile-form" id="profile-and-1">
      <li>
        <label>&nbsp;</label>
      </li>
    </ul> -->
    <div class="clearFix"></div>
    <div class="button-aligner" style="padding-top: 15px">
      <input class="btnSecondary" type="button" value="Get Users Count" id="userCountInCompositeSearchCriteriaButton_<?php echo $mailCriteria?>" onclick="getUserCountInCompositeUserSet(<?php echo $mailCriteria?>);" />
      <span id="userCountInCompositeSearchCriteria_<?php echo $mailCriteria?>" style="margin-left: 10px; font:bold 14px arial;"></span>
    </div>
   
</div>
</div>  
