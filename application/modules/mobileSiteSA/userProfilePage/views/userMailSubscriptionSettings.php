<?php foreach($mailerCategory as $id=>$subsCategory){ 
            if($subsCategory['showFT'] == 1){
              $highlight = ($id == $subscrSetting?'highlight':'');
      ?>
    <div class="email_cols <?php echo $highlight; ?>">
      <div class="col_title">
        <div class="descrpt_block">
          <h3><?php echo $subsCategory['category']; ?></h3>
          <p><?php echo $subsCategory['description']; ?></p>
        </div>
      </div>
      <div class="toggle_bar">
        <input type="checkbox" id="unsubChbox<?php echo $id; ?>" class="ios-toggle" <?php echo ($userUnsubsData[$id] !== 1?'checked="checked"':''); ?> value="<?php echo $id; ?>">
        <label for="unsubChbox<?php echo $id; ?>" class="checkbox-label" data-off="off" data-on="on"></label>
      </div>
    </div>
    <?php }
} ?>