<section class="prf-box-grey" id="communicationPreferenceSection">
    <div class="prft-titl">
        <div class="caption">
            <p>COMMUNICATION PREFERENCES</p>
        </div>
        <div class="tools">
            <a id="changePrefLink" href="javascript:void(0);" style="display: none;" class="change" onclick="$j('#commPrefDiv').show(); $j('#changePrefLink').hide();">Change</a>
        </div>
    </div>
    <!--profile-tab content-->
    
    <div class="email-wrapper">
        <div class="email_title">Email Preferences <span>(Choose only the emails you'd like to receive on your registered email id)</span></div>
        <div class="email_row">
        
        <?php foreach ($mailerUnsubscribeCategory as $id => $val) { 
            if($val['showFT']){
                $checked = "";
                if(!$unsubscribeData[$id]){
                    $checked = "checked='checked'";
                }

                ?>
                <div class="email_cols">
                  <div class="col_title">
                    <div class="descrpt_block">
                      <h3><?php echo $val['category'];?></h3>
                      <p><?php echo $val['description'];?></p>
                    </div>
                  </div>
                  <div class="toggle_bar">
                    <input type="checkbox" name="unsubscribeBtn" id="unsubscribeBtn<?php echo $id?>" class="ios-toggle unsubscribeBtn" data-val="<?php echo $id?>" <?=$checked;?>  >
                    <label for="unsubscribeBtn<?php echo $id?>" class="checkbox-label" data-off="off" data-on="on"></label>
                  </div>
                </div>        
            <?php }?>
        <?php }?>
        </div>
     </div>
    
</section>
  