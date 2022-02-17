<div class="cmn-card mb2 round-border">
    <h2 class="f20 clor3 mb2 f-weight1">Contact</h2>
    <div class="cnt-col">
      <p class="location">Location : 
      <span class="para-2">
          <?php 
            $locationName = $contactDetails['city_name'];
            if($contactDetails['locality_name'])
              $locationName = $contactDetails['locality_name'].", ".$locationName;

            echo $locationName;
          ?>
      </span></p>
      <ul class="ct-ul">
      <?php if(!empty($contactDetails['address'])){?>
              <li>
                <div class="admision-query">
                  <p class="query-head web"><strong>Main Address</strong><span><?=nl2br(htmlentities($contactDetails['address']))?></span></p>
                </div>
              </li>
      <?php } ?>
        <li>
        <?php if(!empty($contactDetails['admission_contact_number']) || !empty($contactDetails['admission_email'])) { ?>
                <div class="admision-query">
                    <p class="query-head">For admission related enquiry</p>
                    <?php if(!empty($contactDetails['admission_contact_number'])) { ?>
                            <p class="c-num"><?=$contactDetails['admission_contact_number']?></p>
                    <?php } ?>
                    <?php if(!empty($contactDetails['admission_email'])) { ?>
                            <p class="c-num"><?=$contactDetails['admission_email']?></p>
                    <?php } ?>
                </div>
        <?php } ?>
        <?php if(!empty($contactDetails['generic_contact_number']) || !empty($contactDetails['generic_email'])){?>
                <div class="general-query">
                    <p class="query-head">For general enquiry</p>
                    <?php if(!empty($contactDetails['generic_contact_number'])) { ?>
                            <p class="c-num"><?=$contactDetails['generic_contact_number']?></p>
                    <?php } ?>
                    <?php if(!empty($contactDetails['generic_email'])) { ?>
                            <p class="c-num"><?=$contactDetails['generic_email']?></p>
                    <?php } ?>
                </div>
        <?php } ?> 
        </li>
        <?php if(!empty($contactDetails['website_url'])){?>
                  <li>
                    <div class="admision-query">
                      <p class="query-head web"><strong>Website</strong><a href="<?=$contactDetails['website_url'];?>"><?php echo $contactDetails['website_url'];?></a></p>
                    </div>
                  </li>
        <?php } ?>
      </ul>
    </div>
</div>